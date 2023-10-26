

INSERT INTO users (firstName, lastName, aboutMe, gender, birthday, nationality, currentLocation, username, email, passwordHash, isAdmin, isActive, isGuide, isPublic)
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
    ('Ricardo', 'Ramos', 'Entusiasta de esportes', 'Male', '1992-06-25', 1, 2, 'ricardor', 'ricardo@email.com', 'password123', false, true, true, true);


INSERT INTO friendRequest (source_id, target_id, status, updated_at)
VALUES
    (1, 2, 'Pending', NOW()),
    (3, 4, 'Accepted', NOW()),
    (5, 6, 'Pending', NOW());


INSERT INTO user_message (source_id, target_id, message)
VALUES
    (1, 2, 'Oi, como vai?'),
    (2, 1, 'Olá! Estou bem, obrigado! E você?');


INSERT INTO user_post (user_id, content)
VALUES
    (1, 'Acabei de assistir a um jogo de futebol!'),
    (2, 'Minha última viagem foi incrível!');


INSERT INTO comment (creator_id, post_id, content)
VALUES
    (1, 1, 'Que jogo emocionante!'),
    (2, 2, 'Adoraria ouvir mais sobre sua viagem.');


INSERT INTO user_Settings (user_id, isGuide, isPublic, darkMode)
VALUES
    (1, false, true, true),
    (2, true, true, false);


INSERT INTO user_notification (notification_id, user_id, notification_type)
VALUES
    (1, 1, 'request_friendship'),
    (2, 2, 'request_friendship');