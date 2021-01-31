<?php


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Objects\Results\LanguagePredictionSentencesResults\LanguagePredictionSentence;

    /**
     * Class LanguagePredictionSentencesResults
     * @package CoffeeHouse\Objects\Results
     */
    class LanguagePredictionSentencesResults
    {
        /**
         * The text that was processed
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * An overall prediction of all the sentences combined
         *
         * @var LanguagePredictionResults|null
         */
        public ?LanguagePredictionResults $LanguagePrediction;

        /**
         * The spam prediction sentences
         *
         * @var LanguagePredictionSentence[]|null
         */
        public ?array $LanguagePredictionSentences;

        /**
         * EmotionPredictionSentencesResults constructor.
         */
        public function __construct()
        {
            $this->Text = null;
            $this->LanguagePrediction = null;
            $this->LanguagePredictionSentences = [];
        }

        /**
         * Calculates the current combined languages value
         *
         * @return LanguagePredictionResults
         * @noinspection DuplicatedCode
         */
        public function calculateCombinedPredictions(): LanguagePredictionResults
        {
            $languagePredictionResults = new LanguagePredictionResults();
            $languagePredictionResults->CLD_Results = [];
            $languagePredictionResults->DLTC_Results = [];
            $languagePredictionResults->LD_Results = [];

            $CombinedPredictionsCount = [
                "CLD" => 0,
                "DLTC" => 0,
                "LD" => 0
            ];

            foreach($this->LanguagePredictionSentences as $languagePredictionSentence)
            {
                if($languagePredictionSentence->LanguagePredictionResults->CLD_Results !== null && count($languagePredictionSentence->LanguagePredictionResults->CLD_Results) > 0)
                {
                    foreach($languagePredictionSentence->LanguagePredictionResults->CLD_Results as $CLD_Result)
                    {
                        $current_index = 0;
                        $result_found = false;

                        foreach($languagePredictionResults->CLD_Results as $languagePrediction)
                        {
                            if($CLD_Result->Language == $languagePrediction->Language)
                            {
                                $languagePredictionResults->CLD_Results[$current_index]->Probability += $CLD_Result->Probability;
                                $CombinedPredictionsCount["CLD"] += 1;
                                $result_found = true;
                                break;
                            }

                            $current_index += 1;
                        }

                        if($result_found == false)
                        {
                            $CombinedPredictionsCount["CLD"] += 1;
                            $languagePredictionResults->CLD_Results[] = $CLD_Result;
                        }
                    }
                }


                if($languagePredictionSentence->LanguagePredictionResults->DLTC_Results !== null && count($languagePredictionSentence->LanguagePredictionResults->DLTC_Results) > 0)
                {
                    foreach($languagePredictionSentence->LanguagePredictionResults->DLTC_Results as $DLTC_Result)
                    {
                        $current_index = 0;
                        $result_found = false;

                        foreach($languagePredictionResults->DLTC_Results as $languagePrediction)
                        {
                            if($DLTC_Result->Language == $languagePrediction->Language)
                            {
                                $languagePredictionResults->DLTC_Results[$current_index]->Probability += $DLTC_Result->Probability;
                                $CombinedPredictionsCount["DLTC"] += 1;
                                $result_found = true;
                                break;
                            }

                            $current_index += 1;
                        }

                        if($result_found == false)
                        {
                            $CombinedPredictionsCount["DLTC"] += 1;
                            $languagePredictionResults->DLTC_Results[] = $DLTC_Result;
                        }
                    }
                }

                if($languagePredictionSentence->LanguagePredictionResults->LD_Results !== null && count($languagePredictionSentence->LanguagePredictionResults->LD_Results) > 0)
                {
                    foreach($languagePredictionSentence->LanguagePredictionResults->LD_Results as $LD_Result)
                    {
                        $current_index = 0;
                        $result_found = false;

                        foreach($languagePredictionResults->LD_Results as $languagePrediction)
                        {
                            if($LD_Result->Language == $languagePrediction->Language)
                            {
                                $languagePredictionResults->LD_Results[$current_index]->Probability += $LD_Result->Probability;
                                $CombinedPredictionsCount["LD"] += 1;
                                $result_found = true;
                                break;
                            }

                            $current_index += 1;
                        }

                        if($result_found == false)
                        {
                            $CombinedPredictionsCount["LD"] += 1;
                            $languagePredictionResults->LD_Results[] = $LD_Result;
                        }
                    }
                }
            }

            if($CombinedPredictionsCount["CLD"] > 0)
            {
                $current_index = 0;

                foreach($languagePredictionResults->CLD_Results as $CLD_Result)
                {
                    $languagePredictionResults->CLD_Results[$current_index]->Probability =
                        ($CLD_Result->Probability / $CombinedPredictionsCount["CLD"]);

                    $current_index += 1;
                }
            }

            if($CombinedPredictionsCount["DLTC"] > 0)
            {
                $current_index = 0;

                foreach($languagePredictionResults->DLTC_Results as $DLTC_Result)
                {
                    $languagePredictionResults->DLTC_Results[$current_index]->Probability =
                        ($DLTC_Result->Probability / $CombinedPredictionsCount["DLTC"]);

                    $current_index += 1;
                }
            }

            if($CombinedPredictionsCount["LD"] > 0)
            {
                $current_index = 0;

                foreach($languagePredictionResults->LD_Results as $LD_Result)
                {
                    $languagePredictionResults->LD_Results[$current_index]->Probability =
                        ($LD_Result->Probability / $CombinedPredictionsCount["LD"]);

                    $current_index += 1;
                }
            }

            $this->LanguagePrediction = $languagePredictionResults;
            return $languagePredictionResults;
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $sentences = [];

            foreach($this->LanguagePredictionSentences as $languagePredictionSentence)
                $sentences = $languagePredictionSentence->toArray();

            return [
                "text" => $this->Text,
                "language_prediction" => $this->LanguagePrediction->toArray(),
                "sentences" => $sentences
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return LanguagePredictionSentencesResults
         * @throws MalformedDataException
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): LanguagePredictionSentencesResults
        {
            $LanguagePredictionSentencesResultsObject = new LanguagePredictionSentencesResults();

            if(isset($data["text"]))
                $LanguagePredictionSentencesResultsObject->Text = $data["text"];

            if(isset($data["sentences"]))
            {
                $LanguagePredictionSentencesResultsObject->LanguagePredictionSentences = [];

                foreach($data["sentences"] as $datum)
                    $LanguagePredictionSentencesResultsObject->LanguagePredictionSentences[] = LanguagePredictionSentence::fromArray($datum);
            }

            if(isset($data["language_prediction"]))
            {
                $LanguagePredictionSentencesResultsObject->LanguagePrediction = LanguagePredictionResults::fromArray($data["language_prediction"]);
            }
            else
            {
                $LanguagePredictionSentencesResultsObject->calculateCombinedPredictions();
            }


            return $LanguagePredictionSentencesResultsObject;
        }
    }