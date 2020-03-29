create table if not exists cookies
(
    id            int auto_increment comment 'Cookie ID',
    date_creation int          null comment 'The unix timestamp of when the cookie was created',
    disposed      tinyint(1)   null comment 'Flag for if the cookie was disposed',
    name          varchar(255) null comment 'The name of the Cookie (Public)',
    token         varchar(255) null comment 'The public token of the cookie which uniquely identifies it',
    expires       int          null comment 'The Unix Timestamp of when the cookie should expire',
    ip_tied       tinyint(1)   null comment 'If the cookie should be strictly tied to the client''s IP Address',
    client_ip     varchar(255) null comment 'The client''s IP Address of the cookie is tied to the IP',
    data          blob         null comment 'ZiProto Encoded Data associated with the cookie',
    constraint cookies_token_uindex
        unique (token),
    constraint sws_id_uindex
        unique (id)
)
    comment 'The main database for Secured Web Sessions library' charset = latin1;

alter table cookies
    add primary key (id);

