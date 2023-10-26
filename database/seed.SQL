
DROP SCHEMA IF EXISTS lbaw_23155 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw_23155;
SET SEARCH_PATH TO lbaw_23155;
--------------------------------------------------------------------------------
-- DROP
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS user_post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS images CASCADE;
DROP TABLE IF EXISTS user_Settings CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS location CASCADE;
DROP TABLE IF EXISTS friendship CASCADE;
DROP TABLE IF EXISTS friendRequest CASCADE;
DROP TABLE IF EXISTS user_message CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS user_notification CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS group_notification CASCADE;
DROP TABLE IF EXISTS group_member CASCADE;
DROP TABLE IF EXISTS group_join_request CASCADE;
DROP TABLE IF EXISTS likes CASCADE;

--------------------------------------------------------------------------------
-- ENUMS
--------------------------------------------------------------------------------

CREATE TYPE friendRequestStatus AS ENUM ('pending', 'accepted', 'declined');
CREATE TYPE tagAlias AS ENUM ('Networking', 'Food', 'Sports', 'Music', 'Culture', 'Party',
                              'Technology', 'Business', 'Education', 'Fun', 'Study');
CREATE TYPE user_notification_type AS ENUM ('accepted_friend_request', 'request_friendship', 'received_message');
CREATE TYPE group_notification_type AS ENUM ('requested_join', 'joined_group', 'accepted_join', 'ban', 'leave_group', 'invite');

--------------------------------------------------------------------------------
-- TABLES
--------------------------------------------------------------------------------
CREATE TABLE location (
  location_id SERIAL PRIMARY KEY,
  city VARCHAR NOT NULL,
  country VARCHAR NOT NULL,
  zipcode VARCHAR NOT NULL,
  address VARCHAR
);

CREATE TABLE images (
  image_id SERIAL PRIMARY KEY,
  image_path VARCHAR NOT NULL
);


CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  profilePicture_ID INTEGER REFERENCES images(image_id),
  firstName VARCHAR NOT NULL,
  lastName VARCHAR NOT NULL,
  aboutMe VARCHAR,
  gender VARCHAR,
  birthday DATE NOT NULL,
  nationality INTEGER NOT NULL REFERENCES location(location_id) ON UPDATE CASCADE,
  currentLocation INTEGER NOT NULL REFERENCES location(location_id) ON UPDATE CASCADE,
  username VARCHAR NOT NULL UNIQUE,
  email VARCHAR NOT NULL UNIQUE,
  passwordHash VARCHAR NOT NULL,
  isAdmin BOOLEAN DEFAULT false,
  isActive BOOLEAN NOT NULL DEFAULT TRUE,
  isGuide BOOLEAN DEFAULT false,
  isPublic BOOLEAN DEFAULT true
);

CREATE TABLE event (
  event_id SERIAL PRIMARY KEY,
  owner_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  title VARCHAR NOT NULL,
  picture INTEGER NOT NULL REFERENCES images(image_id),
  description VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL,
  location_id INTEGER NOT NULL REFERENCES location(location_id) ON UPDATE CASCADE,
  start_date TIMESTAMP NOT NULL,
  end_date TIMESTAMP NOT NULL,
  isFull BOOLEAN,
  maxUsers INTEGER
);

CREATE TABLE friendRequest (
  source_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  target_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  status friendRequestStatus NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (source_id, target_id)
);

CREATE TABLE user_message (
  message_id SERIAL PRIMARY KEY,
  source_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  target_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  sent_at TIMESTAMP NOT NULL DEFAULT NOW(),
  message VARCHAR NOT NULL
);

CREATE TABLE user_post (
  postID SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  event_id INTEGER REFERENCES event(event_id),
  post_image_id INTEGER REFERENCES images(image_id),
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE comment (
  comment_id SERIAL PRIMARY KEY,
  creator_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  post_id INTEGER NOT NULL REFERENCES user_post(postID) ON UPDATE CASCADE,
  content VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE tags (
  tag_id SERIAL PRIMARY KEY,
  target_id INTEGER NOT NULL REFERENCES user_post(postID) ON UPDATE CASCADE,
  tagAlias tagAlias NOT NULL
);

CREATE TABLE user_Settings (
  user_id SERIAL NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  isGuide BOOLEAN DEFAULT false,
  isPublic BOOLEAN DEFAULT true,
  darkMode BOOLEAN DEFAULT false
);

CREATE TABLE notification (
  notification_id SERIAL PRIMARY KEY,
  notified_user INTEGER NOT NULL REFERENCES users(id),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE user_notification (
  notification_id SERIAL PRIMARY KEY REFERENCES notification(notification_id),
  user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  notification_type user_notification_type NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE groups (
  group_id SERIAL PRIMARY KEY,
  owner_id SERIAL NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  picture INTEGER NOT NULL REFERENCES images(image_id),
  name VARCHAR NOT NULL,
  description VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE group_notification (
  notification_id SERIAL PRIMARY KEY REFERENCES notification(notification_id),
  group_id INTEGER NOT NULL REFERENCES groups(group_id) ON UPDATE CASCADE,
  notification_type group_notification_type NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


CREATE TABLE group_member (
  group_id INTEGER NOT NULL REFERENCES groups(group_id) ON UPDATE CASCADE,
  member_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (group_id, member_id)
);

CREATE TABLE group_join_request (
  group_id INTEGER NOT NULL REFERENCES groups(group_id) ON UPDATE CASCADE,
  member_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (group_id, member_id)
);

CREATE TABLE friendship (
  user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  friend_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (user_id, friend_id)
);

CREATE TABLE likes (
  like_id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  post_id INTEGER REFERENCES user_post(postID),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


--------------------------------------------------------------------------------~
-- PERFORMANCE INDEXES 
--------------------------------------------------------------------------------
-- INDEX 01 
CREATE INDEX index_friendship On friendship USING hash(user_id); -- ajuda a procurar amigos de um user

-- INDEX 02
CREATE INDEX index_notification On notification USING hash(notified_user); -- ajuda a procurar notificaçoes de um user

-- INDEX 03
CREATE INDEX post_created_at_index ON user_post(created_at); --ajuda a ordenar posts , vai ser mt usado

--------------------------------------------------------------------------------
-- TEXT SEARCH INDEXES
--------------------------------------------------------------------------------


-- INDEX 04 post_content_index
ALTER TABLE user_post ADD COLUMN tsvectors tsvector;


CREATE OR REPLACE FUNCTION update_tsv_content() RETURNS TRIGGER AS $$
  BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
      NEW.tsvectors = to_tsvector('english', NEW.content);
    END IF;
    RETURN NEW;
  END;
  $$ LANGUAGE plpgsql;

CREATE TRIGGER update_tsv_content_trigger
  BEFORE INSERT OR UPDATE ON user_post
  FOR EACH ROW
  EXECUTE PROCEDURE update_tsv_content();
;
CREATE INDEX post_content_index ON user_post USING GIN(tsvectors);



--INDEX 05 event_search_index

ALTER TABLE event ADD COLUMN tsvectors tsvector;


CREATE OR REPLACE FUNCTION update_tsv_event() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.title), 'A') ||
      setweight(to_tsvector('english', NEW.description), 'B')
    );
  END IF;
  IF TG_OP = 'UPDATE' THEN
    IF (NEW.title <> OLD.title) OR (NEW.description <> OLD.description) THEN
      NEW.tsvectors = (
        setweight(to_tsvector('english', NEW.title), 'A') ||
        setweight(to_tsvector('english', NEW.description), 'B')
      );
    END IF;
  END IF;
    RETURN NEW;
  END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER update_tsv_event_trigger
  BEFORE INSERT OR UPDATE ON event
  FOR EACH ROW
  EXECUTE PROCEDURE update_tsv_event();

CREATE INDEX event_search_index ON event USING GIN(tsvectors);


--------------------------------------------------------------------------------
-- TRIGGERS
--------------------------------------------------------------------------------

-- dá update à data de alteracao do friend request

CREATE OR REPLACE FUNCTION update_friend_request_updated_at()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER friend_request_updated_at_trigger
BEFORE UPDATE ON friendRequest
FOR EACH ROW
EXECUTE FUNCTION update_friend_request_updated_at();

-- cria uma friendship apenas quando um friend request é aceite

CREATE OR REPLACE FUNCTION create_friendship()
RETURNS TRIGGER AS $$
BEGIN
  IF NEW.status = 'accepted' THEN
    INSERT INTO friendship VALUES (NEW.source_id, NEW.target_id);
    INSERT INTO friendship VALUES (NEW.target_id, NEW.source_id);
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_friendship_trigger
AFTER UPDATE ON friendRequest
FOR EACH ROW
EXECUTE FUNCTION create_friendship();

--------------------------------------------------------------------------------
-- TRANSACTIONS
--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION delete_user_with_related_data(id_user INTEGER) RETURNS VOID AS $$
BEGIN
  SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
  BEGIN;
  DELETE FROM friendRequest WHERE source_id = id_user OR target_id = id_user;
  DELETE FROM user_post WHERE user_id = id_user;
  DELETE FROM comment WHERE creator_id = id_user;
  DELETE FROM images WHERE image_id = (SELECT profilePicture_ID FROM users WHERE id = id_user);
  DELETE FROM event WHERE owner_id = id_user;
  DELETE FROM user_settings WHERE user_id = id_user;
  DELETE FROM users WHERE id = id_user;
  COMMIT;
  EXCEPTION
    WHEN OTHERS THEN
      ROLLBACK;
END;
$$ LANGUAGE plpgsql;
