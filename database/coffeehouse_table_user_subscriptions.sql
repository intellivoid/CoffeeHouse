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

