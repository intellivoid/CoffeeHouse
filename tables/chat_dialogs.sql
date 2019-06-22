create table chat_dialogs
(
    id         int(255) auto_increment comment 'Internal Database ID for this message',
    session_id varchar(255) null comment 'The session that''s associated with this message',
    step       int(255)     null comment 'The dialog step which leaded up to this message',
    input      text         null comment 'The user input',
    output     text         null comment 'AI Output',
    timestamp  int(255)     null comment 'Unix Timestamp of this record',
    constraint chat_dialogs_id_uindex unique (id)
) comment 'Table history of chat conversations by dialog steps';
alter table chat_dialogs add primary key (id);

