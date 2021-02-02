<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;

    /**
     * Class PartOfSpeechTag
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class PartOfSpeechTag
    {
        /**
         * Coordinating conjunction
         */
        const CC = "CC";

        /**
         * Cardinal Number
         */
        const CD = "CD";

        /**
         * Determiner
         */
        const DT = "DT";

        /**
         * Existential there
         */
        const EX = "EX";

        /**
         * Foreign word
         */
        const FW = "FW";

        /**
         * Preposition of subordinating conjunction
         */
        const IN = "IN";

        /**
         * Adjective
         */
        const JJ = "JJ";

        /**
         * Adjective, comparative
         */
        const JJR = "JJR";

        /**
         * Adjective, superlative
         */
        const JJS = "JJS";

        /**
         * List item marker
         */
        const LS = "LS";

        /**
         * Modal
         */
        const MD = "MD";

        /**
         * Noun, singular or mass
         */
        const NN = "NN";

        /**
         * Noun, plural
         */
        const NNS = "NNS";

        /**
         * Proper noun, singular
         */
        const NNP = "NNP";

        /**
         * Proper noun, plural
         */
        const NNPS = "NNPS";

        /**
         * Predeterminer
         */
        const PDT = "PDT";

        /**
         * Possessive ending
         */
        const POS = "POS";

        /**
         * Personal pronoun
         */
        const PRP = "PRP";

        /**
         * Possessive noun
         */
        const PRP_ = "PRP$";

        /**
         * Adverb
         */
        const RB = "RB";

        /**
         * Adverb, superlative
         */
        const RBR = "RBR";

        /**
         * Adverb, superlative
         */
        const RBS = "RBS";

        /**
         * Particle
         */
        const RP = "RP";

        /**
         * Symbol
         */
        const SYM = "SYM";

        /**
         * To
         */
        const TO = "TO";

        /**
         * Interjection
         */
        const UH = "UH";

        /**
         * Verb, base form.
         */
        const VB = "VB";

        /**
         * Verb, past tense
         */
        const VBD = "VBD";

        /**
         * Verb, gerund or present participle
         */
        const VBG = "VBG";

        /**
         * Verb, past particple
         */
        const VBN = "VBN";

        /**
         * Verb, non3rd personal singular present
         */
        const VBP = "VBP";

        /**
         * Verb, 3rd person singular present
         */
        const VBZ = "VBZ";

        /**
         * Whdeterminer
         */
        const WDT = "WDT";

        /**
         * Whpronoun
         */
        const WP = "WP";

        /**
         * Possessive whpronoun
         */
        const WP_ = "WP$";

        /**
         * Whadverb
         */
        const WRB = "WRB";
    }