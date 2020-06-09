create table if not exists spam_prediction_cache
(
    id               int(255) auto_increment comment 'Unique Internal Database ID',
    hash             varchar(256) null comment 'The hash of the spam contents',
    ham_calculation  float        null comment 'The calculation of the ham results',
    spam_calculation float        null comment 'The calculation of spam results',
    last_updated     int(255)     null comment 'The Unix Timestamp of when this cache record was last updated',
    created          int(255)     null comment 'The Unix Timestamp of when this cache record was created',
    constraint spam_detection_cache_hash_uindex unique (hash),
    constraint spam_detection_cache_id_uindex unique (id)
) comment 'The cache table for spam prediction';
alter table spam_prediction_cache add primary key (id);