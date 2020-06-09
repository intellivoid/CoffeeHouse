create table if not exists generalized_classification
(
    id              int(255) auto_increment comment 'Internal unique database ID',
    public_id       varchar(256) null comment 'The Public ID for this generalize classifier',
    data            blob         null comment 'The data for calculating the generalized classification results',
    results         float        null comment 'The current results calculated from the data',
    size            int(255)     null comment 'The size of the data range',
    current_pointer int(255)     null comment 'The current position that the pointer is in',
    last_updated    int(255)     null comment 'The Unix Timestamp of when this record was last updated',
    created         int(255)     null comment 'The Unix Timestamp of when this record was created',
    constraint generalized_classification_id_uindex unique (id),
    constraint generalized_classification_public_id_uindex unique (public_id)
) comment 'Table for holding generalized classification data';
alter table generalized_classification add primary key (id);