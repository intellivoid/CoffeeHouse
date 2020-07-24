create table if not exists language_prediction_cache
(
    id           int(255) auto_increment comment 'Internal Unique Database ID',
    hash         varchar(256) null comment 'The SHA256 hash of the input',
    dltc_results blob         null comment 'ZiProto Encoded Data of the DLTC results',
    cld_results  blob         null comment 'ZiProto Encoded Data of the CLD results',
    ld_results   blob         null comment 'ZiProto Encoded Data of the LD results',
    last_updated int(255)     null comment 'The Unix Timestamp of when this cache was last updated',
    created      int(255)     null comment 'The Unix Timestmp of when this record was created',
    constraint language_prediction_cache_hash_uindex unique (hash),
    constraint language_prediction_cache_id_uindex unique (id)
) comment 'Table for storing language prediction caches';

alter table language_prediction_cache add primary key (id);