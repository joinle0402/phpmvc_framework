<?php
namespace application\controllers\client;

use application\core\Controller;
use application\models\AccountModel;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
    public function index()
    {
        $url = "https://tracnghiem.net/thptqg/de-thi-thu-thpt-qg-nam-2022-mon-tieng-anh-5432.html";
        $crawler = loadCrawler($url);

        $quizzesCrawlerResult = $crawler->filter('.exam-content ul li')->each(function(Crawler $liElement) {
            $previousElementsHtml = $liElement->children('h4')->last()->previousAll()->each(function(Crawler $previousElement){
                return $previousElement->html();
            });
            $previousElementsHtml = array_reverse(array_values(array_filter($previousElementsHtml)));

            $nextElementsHtml = $liElement->children('h4')->last()->nextAll()->each(function(Crawler $nextElement) {
                return $nextElement->html();
            });
            $nextElementsHtml = array_map(fn($nextElementHtml) => trim($nextElementHtml), $nextElementsHtml);
            $nextElementsHtml = array_map(fn($nextElementHtml) => str_replace('<h4></h4>', '', $nextElementHtml), $nextElementsHtml);

            return [
                $previousElementsHtml,
                $nextElementsHtml
            ];
        });

        $quizzes = [];
        foreach ($quizzesCrawlerResult as $key => $quizzCrawlerResult)
        {
            $quizzes[$key]['questionInfo'] = $quizzCrawlerResult[0];
            if (count($quizzes[$key]['questionInfo']) > 1)
            {
                $quizzes[$key]['questionInfo']['questionParagraph'] = array_splice($quizzes[$key]['questionInfo'], 1);
                $quizzes[$key]['questionInfo']['questionType'] = $quizzes[$key]['questionInfo'][0];
                unset($quizzes[$key]['questionInfo'][0]);
                $quizzes[$key]['questionInfo']['questionType'] = getTextBetweenTags($quizzes[$key]['questionInfo']['questionType'], 'p');
            }

            if (count($quizzes[$key]['questionInfo']) === 1)
            {
                $quizzes[$key]['questionInfo']['questionType'] = $quizzes[$key]['questionInfo'][0];
                unset($quizzes[$key]['questionInfo'][0]);
                $quizzes[$key]['questionInfo']['questionType'] = getTextBetweenTags($quizzes[$key]['questionInfo']['questionType'], 'p');
            }

            $quizzCrawler = $quizzCrawlerResult[1];
            $quizzCrawlerLength = count($quizzCrawler);

            $quizzes[$key]['question'] = getTextBetweenTags($quizzCrawler[$quizzCrawlerLength - 5], 'p');
            $quizzes[$key]['option1'] = substr($quizzCrawler[$quizzCrawlerLength - 4], 3);
            $quizzes[$key]['option2'] = substr($quizzCrawler[$quizzCrawlerLength - 3], 3);
            $quizzes[$key]['option3'] = substr($quizzCrawler[$quizzCrawlerLength - 2], 3);
            $quizzes[$key]['option4'] = substr($quizzCrawler[$quizzCrawlerLength - 1], 3);
        }

        $examination = [];
        $examination['url'] = $url;
        $examination['name'] = $crawler->filter('.title22Bold', 0)->text();
        $examination['description'] = $crawler->filter('.title22Bold', 0)->nextAll('p', 0)->text();
        $examination['totalQuestion'] = extractDigitNumberInString($crawler->filter('.num-question', 0)->text());
        $examination['totalTime'] = extractDigitNumberInString($crawler->filter('.num-minutes', 0)->text());
        $examination['quizzes'] = $quizzes;

        echo '<pre>';
        print_r($examination);
        echo '</pre>';
        echo '<hr/>';
    }

}

/**
 * if (preg_match_all('@<ul>(?<questions>.*?)</ul>@si', $response, $matches))
{
    $questionsElement = $matches['questions'][0];
    if (preg_match_all('@<li>(.*?)</li>@si', $questionsElement, $matches))
    {
        $questionElement = $matches[0];

        foreach ($matches[0] as $key => $questionElement)
        {
            if (preg_match_all('@<strong.*?>(.*?)</strong>@si', $questionElement, $matches))
            {
                if (strpos($matches[0][0], "Make the") !== false)
                {

                }
                echo '<pre>';
                print_r($matches);
                echo '</pre>';
                echo '<hr/>';
            }

            if (preg_match_all('@<p.*?>(.*?)</p>@si', $questionElement, $matches))
            {
                $numberOfElements = count($matches[1]);
                $description = '';
                $questionTitle = $matches[1][$numberOfElements - 5];
                $optionTitleOne = substr($matches[1][$numberOfElements - 4], 3);
                $optionTitleTwo = substr($matches[1][$numberOfElements - 3], 3);
                $optionTitleThree = substr($matches[1][$numberOfElements - 2], 3);
                $optionTitleFour = substr($matches[1][$numberOfElements - 1], 3);

                for ($index = $numberOfElements - 1; $index >= 6; $index--)
                {
                    $description .= $matches[1][$numberOfElements - $index]."<br/>";
                }

                // if (!empty($description))
                // {
                //     echo "Description: ".$description."</br>";
                // }
                // echo "Câu ".($key + 1).": ".$questionTitle."</br>";
                // echo "A. ".$optionTitleOne."</br>";
                // echo "B. ".$optionTitleTwo."</br>";
                // echo "C. ".$optionTitleThree."</br>";
                // echo "D. ".$optionTitleFour."</br>";
                // echo "</hr>";

                // foreach ($matches[1] as $key => $value)
                // {
                //     echo '<pre>';
                //     var_dump($value);
                //     echo '</pre>';
                // }
                // echo '<hr/>';

                // if (count($matches[1]) === 6)
                // {
                //     array_shift($matches[1]);
                // }

                // echo $matches[1][0]."<br/>";
                // echo $matches[1][1]."<br/>";
                // echo $matches[1][2]."<br/>";
                // echo $matches[1][3]."<br/>";
                // echo $matches[1][4]."<br/>";
                // echo '<hr/>';
            }
        }

    }
}
 *
 *
 */


