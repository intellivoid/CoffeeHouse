create table if not exists large_generalization
(
    id              int(255) auto_increment comment 'Unique Internal Database ID',
    public_id       varchar(256) null comment 'The Public Generalization ID, this isn''t supposed to be unique but rather as a way to find all the generlizaed data',
    top_label       varchar(255) null comment 'The label of the top results',
    top_probability float        null comment 'The probability of the top result',
    data            blob         null comment 'ZiProto encoded blob of the data',
    created         int(255)     null comment 'The Unix Timestamp of when this row was created',
    constraint large_generalization_id_uindex unique (id)
) comment 'Table for storing and predicting large generalization results';

alter table large_generalization add primary key (id);