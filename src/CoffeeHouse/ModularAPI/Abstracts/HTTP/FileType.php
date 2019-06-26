<?php

    namespace ModularAPI\Abstracts\HTTP;

    /**
     * Class FileType
     * @package ModularAPI\Abstracts\HTTP
     */
    abstract class FileType
    {
        const anything = '*';
        const plain = 'plain';
        const html = 'html';
        const javascript = 'javascript';
        const css = 'css';
        const jpeg = 'jpeg';
        const png = 'png';
        const gif = 'gif';
        const bmp = 'bmp';
        const wav = 'wav';
        const midi = 'midi';
        const mpeg = 'mpeg';
        const ogg = 'ogg';
        const mp4 = 'mp4';
        const json = 'json';
        const ecmascript = 'ecmascript';
        const octet_stream = 'octet-stream';
        const pdf = 'pdf';
    }