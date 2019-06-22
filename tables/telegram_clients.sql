create table telegram_clients
(
    id                 int(255) auto_increment comment 'Internal Database ID',
    chat_id            varchar(255) null comment 'The unique chat ID',
    foreign_session_id varchar(255) null comment 'The foreign session ID associated with this client',
    last_updated       int(255)     null comment 'The Unix Timestamp of when this client was last updated',
    created            int(255)     null comment 'The Unix Timestamp of when this client was created',
    constraint telegram_clients_id_uindex unique (id)
) comment 'Table of active telegram clients';
alter table telegram_clients add primary key (id);