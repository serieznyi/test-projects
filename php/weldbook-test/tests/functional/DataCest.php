<?php

use Codeception\Util\HttpCode;

class DataCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function successRequestHaveCorrectStructure(FunctionalTester $I)
    {
        $I->sendGET('/get_table_data');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'status' => 'integer',
            'error' => 'string',
            'data' => [
                'head' => 'array',
                'body' => 'array',
            ],
        ]);
    }

    public function wrongRouteRequestHaveCorrectStructure(FunctionalTester $I)
    {
        $I->sendGET('/wrong_route');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'status' => 'integer',
            'error' => 'string',
            'data' => 'array',
        ]);
    }

    public function headerHaveCorrectStructure(FunctionalTester $I)
    {
        $I->sendGET('/get_table_data');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                'head' => [
                    'id',
                    'pipeline-MAIN_MATERIAL',
                    'welding-TYPE_OF_JOINT',
                    'pipeline-ORG',
                    'pipeline-AREA',
                    'pipeline-LINE_NUMBER',
                    'pipeline-PIPE_CLASS',
                    'pipeline-PB_PIPELINE_CATEGORY',
                    'pipeline-FLUID_CODE',
                    'elements-MEMBER_1',
                    'elements-MEMBER_2',
                    'characteristics-D_INCHES_MEMBER_1',
                    'characteristics-MEMBER_1_DIAMETER_MM',
                    'characteristics-THICKNESS_MEMBER_1_IDENTIFICATION_SCHEDULE',
                    'characteristics-THICKNESS_MEMBER_1_MM',
                    'characteristics-D_INCHES_MEMBER_2',
                    'characteristics-MEMBER_2_DIAMETER_MM',
                    'characteristics-THICKNESS_MEMBER_2_IDENTIFICATION_SCHEDULE',
                    'characteristics-THICKNESS_MEMBER_2_MM',
                    'welding-WELDING_DATE',
                    'welding-WELDING_METHOD',
                    'welding-TYPE_OF_WELDS',
                    'welding-NO_OF_THE_JOINT_AS_PER_AS_BUILT_SURVEY',
                    'welding-WELDER_S_STAMP_ROOT_PASS',
                    'KSS',
                ],
            ],
        ]);
    }

    public function bodyItemHaveCorrectStructure(FunctionalTester $I)
    {
        $I->sendGET('/get_table_data?page=1&limit=500');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'data' => [
                'body' => [
                    [
                        'integer',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'string',
                        'integer',
                        'integer',
                        'string',
                        'float',
                        'integer',
                        'integer',
                        'string',
                        'float',
                        'string',
                        'string',
                        'string',
                        'string|integer',
                        'string',
                        'null|string',
                    ]
                ],
            ],
        ]);
    }

    public function checkWhatFilteringIsWork(FunctionalTester $I)
    {
        $I->sendGET('/get_table_data?pipeline-MAIN_MATERIAL=LTCS111111');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseEquals('{"status":1,"error":"","data":{"head":[],"body":[]}}');
    }
}
