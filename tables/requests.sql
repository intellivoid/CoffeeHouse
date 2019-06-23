CREATE TABLE requests
(
    id INT(255) PRIMARY KEY COMMENT 'The ID of the Request' AUTO_INCREMENT,
    reference_id VARCHAR(255) COMMENT 'The public Refrence ID for the Request',
    execution_time FLOAT COMMENT 'The execution time of the request (in microseconds)',
    timestamp INT(255) COMMENT 'The Unix Timestamp that this request was made',
    client_ip VARCHAR(255) COMMENT 'The IP Address of the client that made the request',
    version VARCHAR(255) COMMENT 'The version of the API that was used',
    module VARCHAR(255) COMMENT 'The Module of the API that was used for this request',
    request_method VARCHAR(255) COMMENT 'The request method that was used',
    request_parameters TEXT COMMENT 'The parameters that was used (JSON Encoded)',
    response_type VARCHAR(255) COMMENT 'The response type given from the server',
    response_code INT(255) COMMENT 'The HTTP Response code given by the server',
    authentication_method VARCHAR(255) COMMENT 'The authentication method used by the user',
    access_key_public_id VARCHAR(255) COMMENT 'The public ID of the access key that was used (From API Key or Certificate)',
    fatal_error BOOL COMMENT 'Indicates if this request ended in a fatal exception which was handled by the server',
    exception_details TEXT COMMENT 'The details of the exception if there was a fatal error'
);
CREATE UNIQUE INDEX requests_id_uindex ON requests (id);
ALTER TABLE requests COMMENT = 'History of all valid requests that were made to the API';