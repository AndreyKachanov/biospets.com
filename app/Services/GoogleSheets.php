<?php

namespace App\Services;

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\Batch\BatchRequest;
use Google_Client;
use Google\Spreadsheet\SpreadsheetService;

use Exception;

class GoogleSheets
{
    protected $worksheet;

    /**
     * GoogleSheets constructor.
     */
    public function __construct()
    {
        $gsTable = config('app.google_sheets_table_name');
        $gsList = config('app.google_sheets_list_name');

        try {
            $path = base_path() . "/" . config('app.google_sheets_secret_file');
            putenv("GOOGLE_APPLICATION_CREDENTIALS=$path");
            $client = new Google_Client;
            $client->useApplicationDefaultCredentials();
            $client->setApplicationName("Profosmotr analyses");
            $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);
            $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
            ServiceRequestFactory::setInstance(
                new DefaultServiceRequest($accessToken)
            );

            $spreadsheet = (new SpreadsheetService)
                ->getSpreadsheetFeed()
                ->getByTitle($gsTable);

            $this->worksheet = $spreadsheet->getWorksheetFeed()->getByTitle($gsList);

        } catch (Exception $e) {
            info("Error connecting to google sheets.");
            info($e->getMessage());
            info($e->getFile());
            info($e->getLine());
            info($e->getCode());
        }
    }

    /**
     * Check count analyses from Google Sheets
     *
     * @return int
     */
    public function checkCountAnalysesFromGs()
    {
        $cellFeed = $this->worksheet->getCellFeed();

        $countCheckRows = 2000;
        $countRows = 0;

        for ($i = 4; $i <= $countCheckRows; $i++) {

            // считываем данные из GS, начиная с 4 строки в массив
            $tmpArr = [];
            $tmpArr[] = ($cellFeed->getCell($i, 1) == null) ? $cellFeed->getCell($i, 1) : trim($cellFeed->getCell($i, 1)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 2) == null) ? $cellFeed->getCell($i, 2) : trim($cellFeed->getCell($i, 2)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 3) == null) ? $cellFeed->getCell($i, 3) : trim($cellFeed->getCell($i, 3)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 4) == null) ? $cellFeed->getCell($i, 4) : trim($cellFeed->getCell($i, 4)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 5) == null) ? $cellFeed->getCell($i, 5) : trim($cellFeed->getCell($i, 5)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 6) == null) ? $cellFeed->getCell($i, 6) : trim($cellFeed->getCell($i, 6)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 7) == null) ? $cellFeed->getCell($i, 7) : trim($cellFeed->getCell($i, 7)->getInputValue());

            // если с 1 по 7 ячейках есть данные, добавляем в счетчик
            if (count(array_diff($tmpArr, [null, '']))) {
                $countRows++;
            }

        }

        return $countRows;
    }

    /**
     * Clear Google Sheets table
     */
    public function clearTable()
    {
        $listFeed = $this->worksheet->getListFeed();
        $arrayFromGs = $listFeed->getEntries();
        $this->updatedCell(4, $arrayFromGs, $clearRows = true);
    }

    /**
     * Clear Google Sheets table - 1700 rows
     */
    public function clearTable1()
    {

        $cellFeed = $this->worksheet->getCellFeed();
        $batchRequest = new BatchRequest();

        $batchRequest->addEntry($cellFeed->createCell(1, 1, 'Обработка'));
        $cellFeed->insertBatch($batchRequest);

        for ($i = 4; $i <= 1700; $i++) {
            $batchRequest->addEntry($cellFeed->createCell($i, 1, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 2, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 3, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 4, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 5, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 6, null));
            $batchRequest->addEntry($cellFeed->createCell($i, 7, null));
        }

        $cellFeed->insertBatch($batchRequest);
    }

    /**
     * Update Google Sheets table
     *
     * @param $analyses
     */
    public function updateTable($analyses)
    {
        $this->updatedCell(4, $analyses, $clearRows = false);
    }

    /**
     * Updated Google Sheets cell
     *
     * @param $startRow
     * @param $arr
     * @param bool $clearRows
     */
    private function updatedCell($startRow, $arr, $clearRows = false)
    {
        $cellFeed = $this->worksheet->getCellFeed();
        $batchRequest = new BatchRequest();

        $batchRequest->addEntry($cellFeed->createCell(1, 1, 'Обработка'));
        $cellFeed->insertBatch($batchRequest);

        foreach ($arr as $item) {
            $batchRequest->addEntry($cellFeed->createCell($startRow, 1, ($clearRows) ? null : $item->code));

            // если $clearRows == true - отправляем null
            // Иначе
            // если анализ в бд удаленный - отправляем статус Неактивный, иначе Активный или Неактивный в зависимости от статуса в бд
            $batchRequest->addEntry($cellFeed->createCell($startRow, 2, ($clearRows) ? null :
                ( ($item->deleted_at != null) ? 'Неактивный' : ( ($item->is_active == 1) ? 'Активный' : 'Неактивный' ) )) );

            $batchRequest->addEntry($cellFeed->createCell($startRow, 3, ($clearRows) ? null : (($item->deleted_at != null) ? 'Удаленный' : 'Существующий')));
            $batchRequest->addEntry($cellFeed->createCell($startRow, 4, ($clearRows) ? null : $item->title));
            $batchRequest->addEntry($cellFeed->createCell($startRow, 5, ($clearRows) ? null : str_replace(".", ",", $item->price)));
            $batchRequest->addEntry($cellFeed->createCell($startRow, 6, ($clearRows) ? null : (($item->discount == null) ? 0 : str_replace(".", ",", $item->discount))));
            $batchRequest->addEntry($cellFeed->createCell($startRow, 7, ($clearRows) ? null : (int)$item->term));

            $startRow++;
        }

        $batchRequest->addEntry($cellFeed->createCell(1, 1, ($clearRows) ? 'Обработка' : 'Загружено'));
        $batchRequest->addEntry($cellFeed->createCell(2, 1, ($clearRows) ? null : date('d.m.Y H:i:s')));
        $cellFeed->insertBatch($batchRequest);
    }

    /**
     * Check Google Sheets structure and data
     *
     * @return array|bool
     */
    public function checkCorrectStructureGsTable()
    {
        $newArray = $this->analysesToArray();

        // если таблица в gs пустая, или есть есть не заполненые ячейки
        if (isset($newArray['error'])) {

            if ($newArray['error'] == 'empty_gs_table') {
                return ['error' => 'empty_gs_table'];
            }

            if ($newArray['error'] == 'number_cells') {
                return ['error' => 'number_cells', 'row' => $newArray['row']];
            }

            if ($newArray['error'] == 'error_spaces') {
                return ['error' => 'error_spaces', 'row' => $newArray['row']];
            }
        }


        // check duplicate keys
        $cellFeed = $this->worksheet->getCellFeed();

        $t = [];
        $j = 4;

        // перегоняем колонку с кодами в массив
        foreach ($newArray as $i) {
            $t[] = $cellFeed->getCell($j, 1)->getInputValue();
            $j++;
        }

        $duplicates = [];
        foreach (array_count_values($t) as $key => $item) {
            if ($item > 1) {
                $duplicates[$key] = $item;
            }
        }

        if (count($duplicates) > 0) {
            return ['error' => 'duplicate_keys', 'duplicates' => $duplicates];
        }

        // проверяем в цикле содержимое ячеек
        foreach ($newArray as $key => $row) {
            // check code format xx.xx.xxx
            if (preg_match('~^(([а-яa-zё0-9]){2})\.((?2){2})\.((?2){3})$~ui', $row[0]) == 0) {
                return [
                    'error_field' => 'Код',
                    'row' => $key + 4,
                    'msg' => 'Поле "Код" должно иметь следующий формат - xx.xx.xxx (русские или латинские символы в верхнем или нижнем регистре, цифры)'
                ];
            }

            // check status cell
            if ($row[1] != 'Активный' && $row[1] != 'Неактивный') {
                return [
                    'error_field' => 'Статус',
                    'row' => $key + 4,
                    'msg' => 'Поле "Статус" должно иметь только следующие значения: Активный, Неактивный'
                ];
            }

            // check condition cell
            if ($row[2] != 'Существующий' && $row[2] != 'Удаленный') {
                return [
                    'error_field' => 'Condition',
                    'row' => $key + 4,
                    'msg' => 'Поле "Condition" должно иметь только следующие значения: Существующий, Удаленный'
                ];
            }

            // check title field on spaces
            if (ctype_space($row[3])) {
                return [
                    'error_field' => 'Название для лаб. анализа',
                    'row' => $key + 4,
                    'msg' => 'Поле "Название для лаб. анализа" не должно иметь одни пробелы'
                ];
            }

            // check title count
            if (iconv_strlen($row[3]) > 1000) {
                return [
                    'error_field' => 'Название для лаб. анализа',
                    'row' => $key + 4,
                    'msg' => 'Поле "Название для лаб. анализа" не должно быть больше 1000 символов'
                ];
            }


            // check price
            if (filter_var($row[4], FILTER_VALIDATE_FLOAT) === false && filter_var($row[4], FILTER_VALIDATE_INT) === false) {
                return [
                    'error_field' => 'Цена руб.',
                    'row' => $key + 4,
                    'msg' => 'Поле "Цена руб." должно быть числом'
                ];
            }

            // check discount
            if (filter_var($row[5], FILTER_VALIDATE_FLOAT) === false && filter_var($row[5], FILTER_VALIDATE_INT) === false) {
                return [
                    'error_field' => 'Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => 'Поле "Цена со скидкой руб." должно быть числом'
                ];
            }

            // price. check count number before ,
            if (strlen((string)floor($row[4])) > 5) {
                return [
                    'error_field' => 'Цена руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена руб.". Целая часть числа не должна превышать 5 символов'
                ];
            }

            //discount. check count number before ,
            if (strlen((string)floor($row[5])) > 5) {
                return [
                    'error_field' => 'Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена со скидкой руб.". Целая часть числа не должна превышать 5 символов'
                ];
            }

            // check term cell
            if (filter_var($row[6], FILTER_VALIDATE_INT) === false) {
                return [
                    'error_field' => 'Срок дн.',
                    'row' => $key + 4,
                    'msg' => 'Значение в поле "Срок дн." должно быть числом'
                ];
            }

            // check term cell
            if ((int)$row[6] > 365 || (int)$row[6] == 0) {
                return [
                    'error_field' => 'Срок дн.',
                    'row' => $key + 4,
                    'msg' => 'Значение в поле  "Срок дн." не должно быть больше 365 или быть равным 0'
                ];
            }

            // check if price && discount == 0
            if (floatval($row[4] == 0) && floatval($row[5] == 0)) {
                return [
                    'error_field' => 'Цена руб., Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена руб." и "Цена со скидкой руб." не могут быть одинаковыми'
                ];
            }


            // если цена меньше скидки
            if (floatval($row[4]) < floatval($row[5])) {
                return [
                    'error_field' => 'Цена руб., Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена руб." не может быть меньше чем "Цена со скидкой руб."'
                ];
            }

            // если цена == скидке
            if (floatval($row[4]) == floatval($row[5])) {
                return [
                    'error_field' => 'Цена руб., Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена руб." не может быть равна "Цена со скидкой руб."'
                ];
            }

            // если цена меньше нуля или цена = 0
            if (floatval($row[4] < 0) || floatval($row[4] == 0)) {
                return [
                    'error_field' => 'Цена со скидкой руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена руб." не может быть меньше или равно 0'
                ];
            }

            // если скидка меньше 0
            if (floatval($row[5] < 0)) {
                return [
                    'error_field' => 'Цена руб.',
                    'row' => $key + 4,
                    'msg' => '"Цена со скидкой руб." не может быть меньше 0'
                ];
            }

            // если срок отрицательный
            if (floatval($row[6] < 0)) {
                return [
                    'error_field' => 'Срок дн.',
                    'row' => $key + 4,
                    'msg' => '"Срок дн." не может быть меньше 0'
                ];
            }

        }

        return true;
    }

    /**
     * Get analyses from Google Sheets table to array
     *
     * @return array|bool
     */
    public function analysesToArray()
    {
        $listFeed = $this->worksheet->getListFeed();

        $arrayAnalyses = [];

        // получаем данные из gs
        $arrayFromGs = $listFeed->getEntries();

        if (count($arrayFromGs) == 0) {
            return ['error' => 'empty_gs_table'];
        }

        // проверяем последнюю строку на пробельные символы
        $lastItem = array_values(array_slice($arrayFromGs, -1))[0];
        $lastItemValues = $lastItem->getValues();
        $trimA = array_map('trim', $lastItemValues);

        //если в последней строке есть пробельные символы
        $lastRowHasSpaces = false;
        if (!array_filter($trimA)) {
            $lastRowHasSpaces = true;
        }

        if ($lastRowHasSpaces) {
            $countArrayFromGs = count($arrayFromGs) -1;
        } else {
            $countArrayFromGs = count($arrayFromGs);
        }

        // перебираем с 4 строки, проверяем есть ли пустые строки с пробелами
        for ($i = 2; $i < $countArrayFromGs; $i++) {

            // обрезаем все пробелы для 1 строки
            $tempA = array_map('trim', $arrayFromGs[$i]->getValues());

            // перебираем массив - проверяем все ли элементы пустые
            // возвращает true если все ячейки пустые и в какой-то есть пробелы
            if (!array_filter($tempA)) {
                return ['error' => 'error_spaces', 'row' => $i + 2];
            // иначе массив чистый -  добавляем в общий массив значения
            } else {
                $arrayAnalyses[] = $arrayFromGs[$i]->getValues();
            }

        }

        // проверяем, есть ли пустые строки "без пробелов"
        $cellFeed = $this->worksheet->getCellFeed();
        $lastRowArrayFromGs = count($arrayFromGs);
        $countCheckRows = 10000;

        $checkRows = [];

        for ($i = $lastRowArrayFromGs + 2; $i <= $countCheckRows; $i++) {

            // перегоняем все ячейки из одной строки из GS в массив
            // если ячейка пустая - записываем null, если есть значение - записываем значение без пробелов
            // если в ячейке в GS были пробелы - в массив запишется пустота ''
            $tmpArr = [];
            $tmpArr[] = ($cellFeed->getCell($i, 1) == null) ? $cellFeed->getCell($i, 1) : trim($cellFeed->getCell($i, 1)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 2) == null) ? $cellFeed->getCell($i, 2) : trim($cellFeed->getCell($i, 2)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 3) == null) ? $cellFeed->getCell($i, 3) : trim($cellFeed->getCell($i, 3)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 4) == null) ? $cellFeed->getCell($i, 4) : trim($cellFeed->getCell($i, 4)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 5) == null) ? $cellFeed->getCell($i, 5) : trim($cellFeed->getCell($i, 5)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 6) == null) ? $cellFeed->getCell($i, 6) : trim($cellFeed->getCell($i, 6)->getInputValue());
            $tmpArr[] = ($cellFeed->getCell($i, 7) == null) ? $cellFeed->getCell($i, 7) : trim($cellFeed->getCell($i, 7)->getInputValue());


            if (!count(array_diff($tmpArr, [null, '']))) {
                $checkRows[$i] = 0;
            } else {
                $checkRows[$i] = 1;
            }

        }

        $arrReverse = array_reverse($checkRows, true);

            $firstOne = array_search('1', $arrReverse);
            foreach($arrReverse as $key => $value){
                if($key == $firstOne)
                    break;

                unset($arrReverse[$key]);
            }

            $emptyRows = array_keys(array_reverse($arrReverse, true), 0);
            if (count($emptyRows) > 0) {
                return ['error' => 'error_spaces', 'row' => $emptyRows];
            }

        // если в gs данных нет
        if (count($arrayAnalyses) == 0) {
            return ['error' => 'empty_gs_table'];
        }

        // проверка на количество ячеек в строке, с учетом что колонки Цена со скидкой может не быть
        $newArray = [];
        foreach ($arrayAnalyses as $key => $item) {

            //если кол-во колонок != 7
            if (count($item) != 7) {
                return ['error' => 'number_cells', 'row' => $key + 4];
            }

            // перегоняем в нумерованный массив, потому что из GS приходит массив с иероглифами типа "_ciyn3"
            foreach (array_values($item) as $key2 => $i) {
                $newArray[$key][$key2] = $i;
            }
        }

        // удаляем из ячеек price и discount пробелы, заменяем запятую на точку
        $tempArray = [];
        foreach ($newArray as $row) {
            $row[4] = preg_replace('/\s+/', '', $row[4]);
            $row[4] = str_replace(",", ".", $row[4]);

            $row[5] = preg_replace('/\s+/', '', $row[5]);
            $row[5] = str_replace(",", ".", $row[5]);

            $tempArray[] = $row;
        }

        return $tempArray;
    }
}