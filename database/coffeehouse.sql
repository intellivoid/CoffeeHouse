create table if not exists chat_dialogs
(
    id         int auto_increment comment 'Internal Database ID for this message',
    session_id varchar(255) null comment 'The session that''s associated with this message',
    step       int          null comment 'The dialog step which leaded up to this message',
    input      text         null comment 'The user input',
    output     text         null comment 'AI Output',
    timestamp  int          null comment 'Unix Timestamp of this record',
    constraint chat_dialogs_id_uindex
        unique (id)
)
    comment 'Table history of chat conversations by dialog steps' charset = latin1;

alter table chat_dialogs
    add primary key (id);

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

create table if not exists foreign_sessions
(
    id           int auto_increment comment 'Internal Session ID',
    session_id   varchar(255) null comment 'Public Session ID to identify this session and it''s properties',
    headers      blob         null comment 'ZiProto encoded HTTP Headers that are used in this session',
    cookies      blob         null comment 'ZiProto encoded HTTP Cookies that are used in this session',
    variables    blob         null comment 'ZiProto encoded HTTP Body request variables that are used in this session',
    language     varchar(20)  null comment 'The language that this session is based in',
    available    tinyint(1)   null comment 'Indicates if this session has been expired by force',
    messages     int          null comment 'The total amount of messages that has been sent to this session',
    expires      int          null comment 'Unix Timestamp of when this session expires',
    last_updated int          null comment 'The Unix Timestamp of when this session was last updated',
    created      int          null comment 'Unix Timestamp of when this session has been created',
    constraint foreign_sessions_id_uindex
        unique (id),
    constraint foreign_sessions_session_id_uindex
        unique (session_id)
)
    comment 'Table for foreign chat sessions (eg; third party bots)' charset = latin1;

alter table foreign_sessions
    add primary key (id);

create table if not exists user_subscriptions
(
    id                int auto_increment comment 'Primary unique internal Database ID for this record',
    account_id        int null comment 'The ID of the user''s Intellivoid Account',
    subscription_id   int null comment 'The ID of the subscription that this user is associated to',
    access_record_id  int null comment 'The ID of the access record ID used for the API',
    status            int null comment 'The status of this user subscription',
    created_timestamp int null comment 'The Unix Timestamp of when this record was created',
    constraint user_subscriptions_access_record_id_uindex
        unique (access_record_id),
    constraint user_subscriptions_account_id_uindex
        unique (account_id),
    constraint user_subscriptions_id_uindex
        unique (id)
)
    comment 'Table of user subscriptions to keep track of the components of the IVA System';

alter table user_subscriptions
    add primary key (id);

