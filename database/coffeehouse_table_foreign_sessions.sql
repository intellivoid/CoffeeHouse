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

