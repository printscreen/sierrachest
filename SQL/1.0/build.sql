CREATE TABLE game_type (
    game_type_id    INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    PRIMARY KEY (game_type_id)
);

CREATE TABLE game_series (
    game_series_id  INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    active          BOOLEAN         NULL,
    PRIMARY KEY (game_series_id)
);

CREATE TABLE ersb (
    ersb_id         INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    description     VARCHAR(255)    NOT NULL,
    age             INT(10)         NOT NULL,
    image           VARCHAR(255)    NOT NULL,
    PRIMARY KEY (ersb_id)
);

CREATE TABLE game (
    game_id         INT(10)         NOT NULL auto_increment,
    title           VARCHAR(255)    NOT NULL,
    description     TEXT            NULL,
    PRIMARY KEY (game_id)
);