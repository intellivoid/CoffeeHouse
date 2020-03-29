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

