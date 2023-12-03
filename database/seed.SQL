--DROP SCHEMA IF EXISTS lbaw_23155 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw_23155;
--SET SEARCH_PATH TO lbaw_23155;
--------------------------------------------------------------------------------
-- DROP
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS images CASCADE;
DROP TABLE IF EXISTS location CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS userPost CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS userSettings CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS friendship CASCADE;
DROP TABLE IF EXISTS friendRequest CASCADE;
DROP TABLE IF EXISTS userMessage CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS userNotification CASCADE;
DROP TABLE IF EXISTS groups CASCADE;
DROP TABLE IF EXISTS groupNotification CASCADE;
DROP TABLE IF EXISTS groupMember CASCADE;
DROP TABLE IF EXISTS groupJoinRrequest CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TYPE IF EXISTS friendRequestStatus_types CASCADE;
DROP TYPE IF EXISTS tagAlias_types CASCADE;
DROP TYPE IF EXISTS user_notification_types CASCADE;
DROP TYPE IF EXISTS group_notification_types CASCADE;
--------------------------------------------------------------------------------
-- ENUMS
--------------------------------------------------------------------------------

CREATE TYPE friendRequestStatus_types AS ENUM ('pending', 'accepted', 'rejected');
CREATE TYPE tagAlias_types AS ENUM ('Networking', 'Food', 'Sports', 'Music', 'Culture', 'Party',
                              'Technology', 'Business', 'Education', 'Fun', 'Study');
CREATE TYPE user_notification_types AS ENUM ('accepted_friend_request', 'request_friendship', 'received_message');
CREATE TYPE group_notification_types AS ENUM ('requested_join', 'joined_group', 'accepted_join', 'ban', 'leave_group', 'invite');

--------------------------------------------------------------------------------
-- TABLES
--------------------------------------------------------------------------------
CREATE TABLE location (
  locationID SERIAL PRIMARY KEY,
  city VARCHAR NOT NULL,
  country VARCHAR NOT NULL,
  zipcode VARCHAR NOT NULL,
  addressName VARCHAR
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
  password VARCHAR NOT NULL,
  isAdmin BOOLEAN DEFAULT false,
  isActive BOOLEAN NOT NULL DEFAULT TRUE,
  isGuide BOOLEAN DEFAULT false,
  isPublic BOOLEAN DEFAULT true
);

CREATE TABLE event (
  eventID SERIAL PRIMARY KEY,
  ownerID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  title VARCHAR NOT NULL,
  pictureID INTEGER REFERENCES images(imageID),
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
  friendRequestState friendRequestStatus_types NOT NULL,
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
  notification_type user_notification_types NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE groups (
  groupID SERIAL PRIMARY KEY,
  ownerID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  pictureID INTEGER REFERENCES images(imageID),
  name VARCHAR NOT NULL,
  description VARCHAR NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE userPost (
  postID SERIAL PRIMARY KEY,
  userID INTEGER NOT NULL REFERENCES users(id) ON UPDATE CASCADE,
  eventID INTEGER REFERENCES event(eventID),
  groupID INTEGER REFERENCES groups(groupID),
  postImageID INTEGER REFERENCES images(imageID),
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE groupNotification (
  notificationID INTEGER PRIMARY KEY REFERENCES notification(notificationID),
  groupID INTEGER NOT NULL REFERENCES groups(groupID) ON UPDATE CASCADE,
  notification_type group_notification_types NOT NULL,
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
  tagAlias tagAlias_types NOT NULL
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
CREATE INDEX index_friendship On friendship USING hash(friendID); -- ajuda a procurar amigos de um user

-- INDEX 02
CREATE INDEX index_notification On notification USING hash(notifiedUser); -- ajuda a procurar notificaçoes de um user

-- INDEX 03
CREATE INDEX post_created_at_index ON userPost(created_at); --ajuda a ordenar posts , vai ser mt usado

-- INDEX 04
CREATE INDEX index_username ON users(username, firstname, lastname);

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


-- INDEX 06 user_search_index

ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION update_tsv_user() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    NEW.tsvectors = (
      setweight(to_tsvector('english', NEW.username), 'A') ||
      setweight(to_tsvector('english', NEW.firstname), 'B') ||
      setweight(to_tsvector('english', NEW.lastname), 'C')
    );
  END IF;
  IF TG_OP = 'UPDATE' THEN
    IF (NEW.username <> OLD.username) OR (NEW.firstname <> OLD.firstname) OR (NEW.lastname <> OLD.lastname) THEN
      NEW.tsvectors = (
        setweight(to_tsvector('english', NEW.username), 'A') ||
        setweight(to_tsvector('english', NEW.firstname), 'B') ||
        setweight(to_tsvector('english', NEW.lastname), 'C')
      );
    END IF;
  END IF;
    RETURN NEW;
  END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER update_tsv_user_trigger
  BEFORE INSERT OR UPDATE ON users
  FOR EACH ROW
  EXECUTE PROCEDURE update_tsv_user();
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
  IF NEW.friendRequestState = 'accepted' THEN
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
  DELETE FROM friendRequest WHERE sourceID = id_user OR targetID = id_user;
  DELETE FROM userPost WHERE userID = id_user;
  DELETE FROM comment WHERE creatorID = id_user;
  DELETE FROM images WHERE imageID = (SELECT profilePictureID FROM users WHERE id = id_user);
  DELETE FROM event WHERE ownerID = id_user;
  DELETE FROM userSettings WHERE userID = id_user;
  DELETE FROM users WHERE id = id_user;
  COMMIT;
  EXCEPTION
    WHEN OTHERS THEN
      ROLLBACK;
END;
$$ LANGUAGE plpgsql;


--------------------------------------------------------------------------------
-- POPULATE
--------------------------------------------------------------------------------

INSERT INTO location (locationID,city, country, zipcode, addressName)
VALUES
    (1,'Porto', 'Portugal','4000', 'Rua do Almada, 4050-032 Porto'),
    (2,'Tokyo', 'Japan','100', '1 Chome-1-1 Oshiage, Sumida City');

INSERT INTO users (firstName, lastName, aboutMe, gender, birthday, nationality, currentLocation, username, email, password, isAdmin, isActive, isGuide, isPublic)
VALUES
    ('João', 'Silva', 'Gosto de futebol', 'Male', '1990-05-15', 1, 1, 'joaosilva', 'joao@email.com', 'password123', false, true, true, true),
    ('Ana', 'Pereira', 'Adoro viajar', 'Female', '1985-09-22', 2, 2, 'anapereira', 'ana@email.com', 'password456', false, true, false, true),
    ('Pedro', 'Santos', 'Amante da música', 'Male', '1995-02-10', 1, 2, 'pedrosantos', 'pedro@email.com', 'password789', false, true, false, true),
    ('Marta', 'Ferreira', 'Apaixonada por fotografia', 'Female', '1987-07-30', 2, 1, 'martaf', 'marta@email.com', 'password123', false, true, true, true),
    ('Luís', 'Ribeiro', 'Fã de esportes radicais', 'Male', '1993-11-18', 1, 1, 'luisr', 'luis@email.com', 'password456', false, true, true, true),
    ('Sofia', 'Gomes', 'Viciada em culinária', 'Female', '1988-04-05', 2, 2, 'sofiag', 'sofia@email.com', 'password789', false, true, false, true),
    ('Rui', 'Machado', 'Aventuras ao ar livre', 'Male', '1991-03-20', 1, 2, 'ruim', 'rui@email.com', 'password123', false, true, true, true),
    ('Carla', 'Oliveira', 'Apaixonada por arte', 'Female', '1986-12-14', 2, 1, 'carlao', 'carla@email.com', 'password456', false, true, false, true),
    ('Hugo', 'Sousa', 'Entusiasta de tecnologia', 'Male', '1997-08-09', 1, 1, 'hugos', 'hugo@email.com', 'password789', false, true, true, true),
    ('Mónica', 'Costa', 'Amante de literatura', 'Female', '1989-06-25', 2, 2, 'monicac', 'monica@email.com', 'password123', false, true, true, true),
    ('António', 'Ramos', 'Adepto de desportos de equipe', 'Male', '1992-01-12', 1, 2, 'antonior', 'antonio@email.com', 'password456', false, true, true, true),
    ('Cláudia', 'Fonseca', 'Apreciadora de filmes', 'Female', '1984-10-08', 2, 1, 'claudiaf', 'claudia@email.com', 'password789', false, true, false, true),
    ('Manuel', 'Lopes', 'Amante de gastronomia', 'Male', '1998-03-05', 1, 2, 'manuell', 'manuel@email.com', 'password123', false, true, true, true),
    ('Rosa', 'Mendes', 'Fã de música clássica', 'Female', '1986-06-14', 2, 1, 'rosam', 'rosa@email.com', 'password456', false, true, false, true),
    ('Gonçalo', 'Pereira', 'Apaixonado por tecnologia', 'Male', '1993-09-28', 1, 1, 'goncalop', 'goncalo@email.com', 'password789', false, true, true, true),
    ('Beatriz', 'Santos', 'Aventuras ao ar livre', 'Female', '1989-11-20', 2, 2, 'beatrizs', 'beatriz@email.com', 'password123', false, true, true, true),
    ('Carlos', 'Ferreira', 'Amante de fotografia', 'Male', '1991-07-18', 1, 2, 'carlosf', 'carlos@email.com', 'password456', false, true, true, true),
    ('Teresa', 'Silva', 'Adepta de desportos radicais', 'Female', '1987-02-23', 2, 1, 'teresas', 'teresa@email.com', 'password789', false, true, false, true),
    ('André', 'Ribeiro', 'Viciado em culinária', 'Male', '1995-04-30', 1, 1, 'andrer', 'andre@email.com', 'password123', false, true, true, true),
    ('Inês', 'Gomes', 'Adoro viajar', 'Female', '1988-12-09', 2, 2, 'inesg', 'ines@email.com', 'password456', false, true, false, true),
    ('Miguel', 'Machado', 'Gosto de música eletrônica', 'Male', '1996-03-15', 1, 2, 'miguelm', 'miguel@email.com', 'password789', false, true, true, true),
    ('Lúcia', 'Oliveira', 'Apaixonada por dança', 'Female', '1985-05-28', 2, 1, 'luciao', 'lucia@email.com', 'password123', false, true, false, true),
    ('Vasco', 'Sousa', 'Amante de história', 'Male', '1994-10-02', 1, 1, 'vascos', 'vasco@email.com', 'password456', false, true, true, true),
    ('Isabel', 'Costa', 'Fã de literatura', 'Female', '1988-09-16', 2, 2, 'isabelc', 'isabel@email.com', 'password789', false, true, true, true),
    ('Ricardo', 'Ramos', 'Entusiasta de esportes', 'Male', '1992-06-25', 1, 2, 'ricardor', 'ricardo@email.com', 'password123', false, true, true, true),
    ('Ruben', 'Esteves', 'Sou um gajo fixe', 'Male', '2002-05-24', 1, 2, 'ruben38esteves', 'ruben38esteves@gmail.com','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', true, true, true, true),
    ('Miguel', 'Dionisio', 'Sou um gajo fixe', 'Male', '2002-08-14', 1, 2, 'miguelmdionisio', 'miguelmdionisio@gmail.com','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', true, true, true, false);


INSERT INTO friendRequest (sourceID, targetID, friendRequestState, updated_at)
VALUES
    (1, 2, 'pending', NOW()),
    (3, 4, 'accepted', NOW()),
    (5, 6, 'pending', NOW());

INSERT INTO friendship (userID, friendID)
VALUES
    (26, 1),
    (1, 26),
    (26, 2),
    (2, 26),
    (3, 4),
    (4, 3);
  
INSERT INTO userMessage (sourceID, targetID, message)
VALUES
    (1, 2, 'Oi, como vai?'),
    (2, 1, 'Olá! Estou bem, obrigado! E você?');

INSERT INTO groups (ownerID, name, description)
VALUES
    (26, 'Grupo de amigos', 'Grupo de amigos que gostam de se divertir!');

INSERT INTO userPost (userID, content, created_at)
VALUES
    (1, 'Acabei de assistir a um jogo de futebol!', '2021-05-24 20:00:00'),
    (2, 'Acabei de voltar de uma viagem!', '2021-05-25 04:00:00'),
    (26, 'segund avez que bebi cervejas!', '2021-05-24 05:00:00'),
    (12, 'Que dia lindo!', '2021-05-23 04:00:00'),
    (2, 'Minha última viagem foi incrível!', '2021-05-22 02:00:00'),
    (2, 'primeiro post!', '2021-05-22 01:00:00'),
    (2, 'segundo post!', '2021-05-22 03:00:00'),
    (26, 'primeira vez que bebi cervejas !', '2021-05-24 04:00:00'),
    (12, 'segundo post!', '2021-05-22 02:00:00');

INSERT INTO userPost (userID, groupID, content, created_at)
VALUES
    (26, 1, 'Isto vai muito mesmoe ser incrivel!', '2021-05-24 20:00:00');


INSERT INTO comment (creatorID, postID, content)
VALUES
    (1, 1, 'Que jogo emocionante!'),
    (2, 2, 'Adoraria ouvir mais sobre sua viagem.');


INSERT INTO userSettings (userID, isGuide, isPublic, darkMode)
VALUES
    (1, false, true, true),
    (2, true, true, false);

INSERT INTO notification (notificationID, notifiedUser)
VALUES
    (1, 1),
    (2, 2);

INSERT INTO userNotification (notificationID, userID, notification_type)
VALUES
    (1, 1, 'request_friendship'),
    (2, 2, 'request_friendship');

INSERT INTO event (eventID, ownerID, title, description, created_at, locationID, startDate, endDate)
VALUES
    (1, 26, 'Festa!!!!!', 'Festa em minha casa!!!!', '2023-11-21 20:00:00',1, '2024-05-24 20:00:00', '2021-05-25 04:00:00');

INSERT INTO userPost (userID, eventID, content)
VALUES
    (26, 1, 'Isto vai ser incrivel!'),
    (26, 1, 'Acreditem, vai mesmo!'),
    (26, 1, 'É o meu aniversario... Venham por favor...');