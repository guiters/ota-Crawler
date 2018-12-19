<?php
include_once "page.php";
class otaCrawler
{


    function clearTag($tag)
    {
        return trim(preg_replace('/\s+/', ' ', $tag));
    }

    function set_nested_array_value(&$array, $path, &$value, $delimiter = '/')
    {
        $pathParts = explode($delimiter, $path);

        $current = &$array;
        foreach ($pathParts as $key) {
            $current = &$current[$key];
        }

        $backup = $current;
        $current = $value;

        return $backup;
    }

    function getContent($html, $tag, $parrent = null)
    {
        $page = new GetPage();
        $url = "https://www.pilotfishtechnology.com/modelviewers/OTA/model/";
        $body = $html->find('BODY', 0);
        foreach ($body->find("table[class=InfoTable]") as $tb) {
            $TBtitle = $tb->find("tr[class=HeaderRow]", 0);
            $title = trim(preg_replace('/\s+/', ' ', $TBtitle->plaintext));
            if ($title === "Elements") {
                foreach ($tb->find("tr[class=DetailRow] td[class=ElementName] a") as $dr) {
                    if ($parrent) {
                        $_SESSION[$parrent][$tag]["Elements"][$this->clearTag($dr->plaintext)] = "";
                    } else {
                        $_SESSION[$tag]["Elements"][$this->clearTag($dr->plaintext)] = "";
                    }
                    echo "  Elements->" .$parrent. "/" . $dr->plaintext . PHP_EOL;
                    $start = $page->downloadPage($url . $dr->href);
                    $html2 = str_get_html($start);
                    $this->getContent($html2, $dr->plaintext, $parrent . "/" . $tag);
                   
                }
            }
            if ($title === "Attributes") {
                foreach ($tb->find("tr[class=DetailRow] td[class=ElementName] a") as $dr) {
                    echo "  Attributes->" . $dr->plaintext . PHP_EOL;
                    if ($parrent) {
                        $_SESSION[$parrent][$tag]["Attributes"][$this->clearTag($dr->plaintext)] = "";
                    } else {
                        $_SESSION[$tag]["Attributes"][$this->clearTag($dr->plaintext)] = "";
                    }
                }
            }
            echo PHP_EOL;
        }
    }
}
?>