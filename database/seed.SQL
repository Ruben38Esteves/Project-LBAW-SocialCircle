DROP SCHEMA IF EXISTS lbaw_23155 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw_23155;
SET SEARCH_PATH TO lbaw_23155;
--------------------------------------------------------------------------------
-- DROP
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS userPost CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS images CASCADE;
DROP TABLE IF EXISTS userSettings CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS location CASCADE;
DROP TABLE IF EXISTS friendship CASCADE;
DROP TABLE IF EXISTS friendRequest CASCADE;
DROP TABLE IF EXISTS userMessage CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS userNotification CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS groupNotification CASCADE;
DROP TABLE IF EXISTS groupMember CASCADE;
DROP TABLE IF EXISTS groupJoinRrequest CASCADE;
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
  locationID SERIAL PRIMARY KEY,
  city VARCHAR NOT NULL,
  country VARCHAR NOT NULL,
  zipcode VARCHAR NOT NULL,
  address VARCHAR
);

CREATE TABLE images (
  imageID SERIAL PRIMARY KEY,
  imagePath VARCHAR NOT NULL
);


CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  profilePictureID INTEGER REFERENCES images(imageID),
  firstName VARCHAR NOT NULL,
  lastName VARCHAR NOT NULL,
  aboutMe VARCHAR,
  gender VARCHAR,
  birthday DATE NOT NULL,
  nationality INTEGER NOT NULL REFERENCES location(locationID) ON UPDATE CASCADE,
  currentLocation INTEGER NOT NULL REFERENCES location(locationID) ON UPDATE CASCADE,
  username VARCHAR NOT NULL UNIQUE,
  email VARCHAR NOT NULL UNIQUE,
  passwordHash VARCHAR NOT NULL,
  isAdmin BOOLEAN DEFAULT false,
  isActive BOOLEAN NOT NULL DEFAULT TRUE,
  isGuide BOOLEAN DEFAULT false,
  isPublic BOOLEAN DEFAULT true
);

CREATE TABLE event (
  eventID SERIAL PRIMARY KEY,
  ownerID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  title VARCHAR NOT NULL,
  pictureID INTEGER NOT NULL REFERENCES images(imageID),
  description VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL,
  locationID INTEGER NOT NULL REFERENCES location(locationID) ON UPDATE CASCADE,
  startDate TIMESTAMP NOT NULL,
  endDate TIMESTAMP NOT NULL,
  isFull BOOLEAN,
  maxUsers INTEGER
);

CREATE TABLE friendRequest (
  sourceID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  targetID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  status friendRequestStatus NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (sourceID, targetID)
);

CREATE TABLE userMessage (
  messageID SERIAL PRIMARY KEY,
  sourceID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  targetID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  sent_at TIMESTAMP NOT NULL DEFAULT NOW(),
  message VARCHAR NOT NULL
);

CREATE TABLE userPost (
  postID SERIAL PRIMARY KEY,
  userID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  eventID INTEGER REFERENCES event(eventID),
  postImageID INTEGER REFERENCES images(imageID),
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE comment (
  commentID SERIAL PRIMARY KEY,
  creatorID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  postID INTEGER NOT NULL REFERENCES userPost(postID) ON UPDATE CASCADE,
  content VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE tags (
  tagID SERIAL PRIMARY KEY,
  targetID INTEGER NOT NULL REFERENCES userPost(postID) ON UPDATE CASCADE,
  tagAlias tagAlias NOT NULL
);

CREATE TABLE userSettings (
  userID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  isGuide BOOLEAN DEFAULT false,
  isPublic BOOLEAN DEFAULT true,
  darkMode BOOLEAN DEFAULT false
);

CREATE TABLE notification (
  notificationID SERIAL PRIMARY KEY,
  notifiedUser INTEGER NOT NULL REFERENCES users(id),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE userNotification (
  notificationID INTEGER PRIMARY KEY REFERENCES notification(notificationID),
  userID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  notification_type user_notification_type NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE groups (
  groupID SERIAL PRIMARY KEY,
  ownerID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  pictureID INTEGER NOT NULL REFERENCES images(imageID),
  name VARCHAR NOT NULL,
  description VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE groupNotification (
  notificationID INTEGER PRIMARY KEY REFERENCES notification(notificationID),
  groupID INTEGER NOT NULL REFERENCES groups(froupID) ON UPDATE CASCADE,
  notification_type group_notification_type NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


CREATE TABLE groupMember (
  groupID INTEGER NOT NULL REFERENCES groups(groupID) ON UPDATE CASCADE,
  memberID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (groupID, memberID)
);

CREATE TABLE groupJoinRrequest (
  groupID INTEGER NOT NULL REFERENCES groups(groupID) ON UPDATE CASCADE,
  memberID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (groupID, memberID)
);

CREATE TABLE friendship (
  userID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  friendID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  PRIMARY KEY (userID, friendID)
);

CREATE TABLE likes (
  likeID SERIAL PRIMARY KEY,
  userID  INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  postID INTEGER REFERENCES userPost(postID),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


--------------------------------------------------------------------------------~
-- PERFORMANCE INDEXES 
--------------------------------------------------------------------------------
-- INDEX 01 
CREATE INDEX index_friendship On friendship USING hash(id); -- ajuda a procurar amigos de um user

-- INDEX 02
CREATE INDEX index_notification On notification USING hash(notifiedUser); -- ajuda a procurar notificaçoes de um user

-- INDEX 03
CREATE INDEX post_created_at_index ON userPost(created_at); --ajuda a ordenar posts , vai ser mt usado

--------------------------------------------------------------------------------
-- TEXT SEARCH INDEXES
--------------------------------------------------------------------------------


-- INDEX 04 post_content_index
ALTER TABLE userPost ADD COLUMN tsvectors tsvector;


CREATE OR REPLACE FUNCTION update_tsv_content() RETURNS TRIGGER AS $$
  BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
      NEW.tsvectors = to_tsvector('english', NEW.content);
    END IF;
    RETURN NEW;
  END;
  $$ LANGUAGE plpgsql;

CREATE TRIGGER update_tsv_content_trigger
  BEFORE INSERT OR UPDATE ON userPost
  FOR EACH ROW
  EXECUTE PROCEDURE update_tsv_content();
;
CREATE INDEX post_content_index ON userPost USING GIN(tsvectors);



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
    INSERT INTO friendship VALUES (NEW.sourceID, NEW.targetID);
    INSERT INTO friendship VALUES (NEW.targetID, NEW.sourceID);
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
  DELETE FROM friendRequest WHERE sourceID = id_user OR targetID = id_user;
  DELETE FROM userPost WHERE userID = id_user;
  DELETE FROM comment WHERE creatorID = id_user;
  DELETE FROM images WHERE imageID = (SELECT profilePictureID FROM users WHERE id = id_user);
  DELETE FROM event WHERE ownerID = id_user;
  DELETE FROM user_settings WHERE userID = id_user;
  DELETE FROM users WHERE id = id_user;
  COMMIT;
  EXCEPTION
    WHEN OTHERS THEN
      ROLLBACK;
END;
$$ LANGUAGE plpgsql;
