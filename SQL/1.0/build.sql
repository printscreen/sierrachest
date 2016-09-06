CREATE TABLE game_type (
    game_type_id    INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    PRIMARY KEY (game_type_id)
);

CREATE TABLE game_series (
    game_series_id  INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    PRIMARY KEY (game_series_id)
);

CREATE TABLE esrb (
    esrb_id         INT(10)         NOT NULL auto_increment,
    name            VARCHAR(255)    NOT NULL,
    description     VARCHAR(255)    NOT NULL,
    age             INT(10)         NOT NULL,
    image           VARCHAR(255)    NOT NULL,
    PRIMARY KEY (esrb_id)
);

CREATE TABLE game (
    game_id             INT(10)         NOT NULL auto_increment,
    slug                VARCHAR(255)    NOT NULL,
    title               VARCHAR(255)    NOT NULL,
    description         TEXT            NULL,
    cover_art           VARCHAR(255)    NULL,
    release_date        DATE            NOT NULL,
    system_requirements TEXT            NULL,
    esrb_id             INT(10)         NULL,
    banner              TEXT            NULL,
    gog_link            TEXT            NULL,
    ebay_link           TEXT            NULL,
    completion_date     DATE            NULL,
    insert_ts           DATETIME        DEFAULT CURRENT_TIMESTAMP,
    update_ts           DATETIME        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (game_id),
    FOREIGN KEY (esrb_id) REFERENCES esrb(esrb_id),
    UNIQUE(slug)
);

CREATE TABLE box (
    box_id              INT(10)         NOT NULL auto_increment,
    active              BOOLEAN         NOT NULL,
    game_id             INT(10)         NOT NULL,
    upca                VARCHAR(255)    NULL,
    page                INT(10)         NOT NULL,
    content             TEXT            NULL,
    title               TEXT            NULL,
    complete            BOOLEAN         NULL,
    spine               VARCHAR(255)    NULL,
    height              INT(10)         NULL,
    width               INT(10)         NULL,
    digital             BOOLEAN         NULL,
    PRIMARY KEY (box_id),
    FOREIGN KEY (game_id) REFERENCES game(game_id)
);

CREATE TABLE news (
    news_id             INT(10)         NOT NULL auto_increment,
    date                DATE            NOT NULL,
    title               VARCHAR(255)    NULL,
    content             TEXT            NULL,
    blurb               TEXT            NOT NULL,
    image               VARCHAR(255)    NULL,
    external_url        TEXT            NULL,
    active              BOOLEAN         NOT NULL,
    PRIMARY KEY (news_id)
);

CREATE TABLE store (
    store_id            INT(10)         NOT NULL auto_increment,
    name                VARCHAR(255)    NOT NULL,
    url                 TEXT            NULL,
    shipping            TEXT            NULL,
    country             VARCHAR(255)    NULL,
    image               VARCHAR(255)    NULL,
    description         TEXT            NOT NULL,
    email               VARCHAR(255)    NULL,
    joined              DATE            NOT NULL,
    PRIMARY KEY (store_id)
);

CREATE TABLE store_item (
    store_item_id       INT(10)         NOT NULL auto_increment,
    title               VARCHAR(255)    NOT NULL,
    store_id            INT(10)         NOT NULL,
    game_id             INT(10)         NOT NULL,
    box_id              INT(10)         NOT NULL,
    url                 TEXT            NOT NULL,
    list_date           DATE            NOT NULL,
    expiration          DATE            NOT NULL,
    comments            TEXT            NOT NULL,
    image               TEXT            NOT NULL,
    auction             BOOLEAN         NOT NULL,
    swap                BOOLEAN         NOT NULL,
    fixed_price         BOOLEAN         NOT NULL,
    price               VARCHAR(255)    NOT NULL,
    currency            VARCHAR(255)    NOT NULL,
    digital             BOOLEAN         NOT NULL,
    display_number      INT(10)         NULL,
    PRIMARY KEY (store_item_id),
    FOREIGN KEY (store_id) REFERENCES store(store_id),
    FOREIGN KEY (game_id) REFERENCES game(game_id),
    FOREIGN KEY (box_id) REFERENCES box(box_id)
);