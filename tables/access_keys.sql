CREATE TABLE access_keys
(
    id INT(255) PRIMARY KEY COMMENT 'The ID of the Access Key' AUTO_INCREMENT,
    public_id VARCHAR(255) COMMENT 'The Public ID of the Access Key',
    public_key VARCHAR(255) COMMENT 'The Public Key for API Usage',
    state INT(255) COMMENT 'The state of the access key',
    usage_data TEXT COMMENT 'Usage data which determines if the Access Key can still be used',
    permissions TEXT COMMENT 'Permissions data which determines what modules that this access key has access to',
    analytics TEXT COMMENT 'The Analytical data regarding the usage of this access key for this month and the past month',
    signatures TEXT COMMENT 'Encryption Signatures for authentication purposes',
    creation_date INT(255) COMMENT 'The Unix Timestamp that this Access Key was created in'
);
CREATE UNIQUE INDEX access_keys_id_uindex ON access_keys (id);
ALTER TABLE access_keys COMMENT = 'The table of available access keys to use with the API';