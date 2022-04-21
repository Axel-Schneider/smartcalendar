<?php

class Calendar
{
    public function Get_HTML(
        $events = null,
        $view_year = null,
        $view_month = null,
        $view_day = null,
        $post_to = null,
        $class = null,
        $button_args = null
    ) {
        $result = '';

// SETUP VALUES:
        $view_date_time = new DateTime();
        $view_date_time->setDate($view_year, $view_month, $view_day);

        $this_year = self::Get_This_Year();
        $this_month = self::Get_This_Month();
        $this_day = self::Get_This_Day();

        if ( ! $view_year)
        {
            $view_year = $this_year;
        }
        if ( ! $view_month)
        {
            $view_month = $this_month;
        }

        if ( ! $view_day)
        {
            $no_day_selected = true;
            $view_day = $this_day;
        }

        $day = 1;
        $next = 1;

// LOOP TO MAKE "WEEK" CALENDAR ROWS:
        for ($week = 0; $week < self::Get_Calendar_Rows($view_year, $view_month); $week++)
        {
            $row = '';

            if (0 == $week && self::Get_Month_Start_Weekday($view_year, $view_month) > 0)
            {
                for ($last = (self::Get_Days_In_Prior_Month($view_year, $view_month) - self::Get_Month_Start_Weekday($view_year, $view_month) + 1); $last <= self::Get_Days_In_Prior_Month($view_year, $view_month); $last++)
                {
                    $row .= self::Build_Cell($post_to, (($view_month > 1) ? $view_year : $view_year - 1), (($view_month > 1) ? $view_month - 1 : 12), $last, ((isset($events[self::Get_ISO_Date((($view_month > 1) ? $view_year : $view_year - 1), (($view_month > 1) ? $view_month - 1 : 12), $last)])) ? $events[self::Get_ISO_Date((($view_month > 1) ? $view_year : $view_year - 1), (($view_month > 1) ? $view_month - 1 : 12), $last)] : null), 'unfocus');
                }

                for ($day = 1; $day <= (7 - self::Get_Month_Start_Weekday($view_year, $view_month)); $day++)
                {
                    $row .= self::Build_Cell($post_to, $view_year, $view_month, $day, ((isset($events[self::Get_ISO_Date($view_year, $view_month, $day)])) ? $events[self::Get_ISO_Date($view_year, $view_month, $day)] : null), (($view_month == $this_month && $view_year == $this_year && $day == $this_day) ? 'today' : (( ! $no_day_selected && $day == $view_day) ? 'selected' : '')));
                }
            }
            else
            {
                for ($i = 0; $i < 7; $i++)
                {
                    if (checkdate($view_month, $day, $view_year))
                    {
                        $row .= self::Build_Cell($post_to, $view_year, $view_month, $day, ((isset($events[self::Get_ISO_Date($view_year, $view_month, $day)])) ? $events[self::Get_ISO_Date($view_year, $view_month, $day)] : null), (($view_month == $this_month && $view_year == $this_year && $day == $this_day) ? 'today' : (( ! $no_day_selected && $day == $view_day) ? 'selected' : '')));
                        $day++;
                    }
                    else
                    {
                        $row .= self::Build_Cell($post_to, (($view_month < 12) ? $view_year : $view_year + 1), (($view_month < 12) ? $view_month + 1 : 1), $next, ((isset($events[self::Get_ISO_Date((($view_month < 12) ? $view_year : $view_year + 1), (($view_month < 12) ? $view_month + 1 : 1), $next + 1)]) && isset($events[self::Get_ISO_Date((($view_month < 12) ? $view_year : $view_year + 1), (($view_month < 12) ? $view_month + 1 : 1), $next++)])) ? $events[self::Get_ISO_Date((($view_month < 12) ? $view_year : $view_year + 1), (($view_month < 12) ? $view_month + 1 : 1), $next++)] : null), 'unfocus');
                    }
                }
            }

            if (0 < strlen($row))
            {
                $result .= HTML_div::Get_HTML($row, ['class' => 'week']);
            }
        }

// ADD CALENDAR TITLE (MONTH/YEAR), NAVIGATION & WEEKDAY LABELS:
        $result = HTML_div::Get_HTML(HTML_div::Get_HTML(self::Get_Calendar_Nav_Button($view_year, $view_month, 'back', $button_args) . self::Get_Calendar_Label_HTML($view_year, $view_month) . self::Get_Calendar_Nav_Button($view_year, $view_month, 'next', $button_args), ['class' => 'calendar_nav clearfix']) . self::Get_Weekday_Labels_HTML(), ['class' => 'calendar_head']) . $result;

// RETURN WRAPPED CALENDAR:
        return HTML_div::Get_HTML($result, ['class' => ((0 < strlen($class)) ? $class . ' ' : '') . 'month ' . strtolower(self::Get_Month_As_String($view_month))]);

    }

    public function Get_Cal_Nav(array $args = null)
    {
        $prev_month = (1 == $args['view_month']) ? 12 : $args['view_month'] - 1;
        $next_month = (12 > $args['view_month']) ? $args['view_month'] + 1 : 1;

        $prev_year = (1 < $args['view_month']) ? $args['view_year'] : $args['view_year'] - 1;
        $next_year = (12 == $args['view_month']) ? $args['view_year'] + 1 : $args['view_year'];

        $args['data-ajax_target'] = (isset($args['data-ajax_target']) && strlen($args['data-ajax_target'])) ? $args['data-ajax_target'] : '';
        $args['data-ajax_call_before'] = (isset($args['data-ajax_call_before']) && strlen($args['data-ajax_call_before'])) ? $args['data-ajax_call_before'] : '';
        $args['data-ajax_call_after'] = (isset($args['data-ajax_call_after']) && strlen($args['data-ajax_call_after'])) ? $args['data-ajax_call_after'] : '';

        $nav_links[] = HTML_a::Get_HTML(self::Get_Month_As_String($prev_month) . ' ' . $prev_year, [
            'href'                  => $args['url'] . $prev_year . '/' . $prev_month . '/',
            'class'                 => 'prev_month' . (($args['class']) ? ' ' . $args['class'] : ''),
            'data-ajax_target'      => $args['data-ajax_target'],
            'data-ajax_call_before' => $args['data-ajax_call_before'],
            'data-ajax_call_after'  => $args['data-ajax_call_after'],
            'title'                 => 'Select to move back to ' . self::Get_Month_As_String($prev_month) . ' ' . $prev_year . '.',
        ]);

        $nav_links[] = HTML_a::Get_HTML('THIS MONTH', [
            'href'                  => $args['url'] . self::Get_This_Year() . '/' . self::Get_This_Month() . '/',
            'class'                 => 'this_month' . (($args['class']) ? ' ' . $args['class'] : ''),
            'data-ajax_target'      => $args['data-ajax_target'],
            'data-ajax_call_before' => $args['data-ajax_call_before'],
            'data-ajax_call_after'  => $args['data-ajax_call_after'],
            'title'                 => 'Select to jump to the current calendar month.',
        ]);

        $nav_links[] = HTML_a::Get_HTML(self::Get_Month_As_String($next_month) . ' ' . $next_year, [
            'href'                  => $args['url'] . $next_year . '/' . $next_month . '/',
            'class'                 => 'next_month' . (($args['class']) ? ' ' . $args['class'] : ''),
            'data-ajax_target'      => $args['data-ajax_target'],
            'data-ajax_call_before' => $args['data-ajax_call_before'],
            'data-ajax_call_after'  => $args['data-ajax_call_after'],
            'title'                 => 'Select to move ahead to ' . self::Get_Month_As_String($next_month) . ' ' . $next_year . '.',
        ]);

        return HTML_div::Get_HTML(HTML_List::Get_HTML('ul', $nav_links), ['class' => 'cal_nav']);
    }

    public function Get_Days_In_Month($year, $month)
    {
        for ($day = 31; $day > 0; $day--)
        {
            if (checkdate($month, $day, $year))
            {
                return $day;
            }
        }

        return false;
    }

    public function Get_Days_In_Prior_Month($year, $month)
    {
        $year = ($month == 1) ? $year - 1 : $year;
        $month = ($month == 1) ? 12 : $month - 1;

        return self::Get_Days_In_Month($year, $month);

    }

    public function Get_Month_As_String($month)
    {
        $date = new DateTime();
        $date->setDate(self::Get_This_Year(), $month, 1);

        return $date->format('F');
    }

    public function Get_ISO_Date($year = null, $month = null, $day = null)
    {
        return sprintf("%04d-%02d-%02d", (($year) ? $year : self::Get_This_Year()), (($month) ? $month : self::Get_This_Month()), (($day) ? $day : self::Get_This_Day()));
    }

    public function Get_This_Year($format = 'Y')
    {
        return date_format(new DateTime, $format);
    }

    public function Get_This_Month($format = 'n')
    {
        return date_format(new DateTime, $format);
    }

    public function Get_This_Day($format = 'j')
    {
        return date_format(new DateTime, $format);
    }

    public function Get_Now($format = 'Y-m-d H:i:s')
    {
        return date_format(new DateTime, $format);
    }

    public function Get_DB_DateTime($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    public function Get_Calendar_Rows($year, $month)
    {
        return ceil((self::Get_Month_Start_Weekday($year, $month) + self::Get_Days_In_Month($year, $month)) / 7);
    }

    public function Get_Calendar_Label_HTML($year, $month)
    {
        return HTML_div::Get_HTML(HTML_div::Get_HTML(HTML_span::Get_HTML(self::Get_Month_As_String($month), ['id' => 'calendar_month_label']), ['class' => 'month']) . ' ' . HTML_div::Get_HTML(HTML_span::Get_HTML($year), ['class' => 'year']), ['class' => 'calendar_label']);
    }

    public function Get_Calendar_Nav_Button($year = 2012, $month = 1, $direction = 'back', array $button_args)
    {
        $prev_month = (1 == $month) ? 12 : $month - 1;
        $next_month = (12 > $month) ? $month + 1 : 1;

        $prev_year = (1 < $month) ? $year : $year - 1;
        $next_year = (12 == $month) ? $year + 1 : $year;

        $direction = (('back' == $direction || 'next' == $direction)) ? $direction : 'back';
        $button_label = (isset($button_args['label']) && strlen($button_args['label'])) ? $button_args['label'] : (('back' == $direction) ? '&#9668;' : '&#9658;');

        if (isset($button_args['href']) && strlen($button_args['href']))
        {
            $button_args['href'] .= ('back' == $direction) ? $prev_year . '/' . $prev_month . '/' : $next_year . '/' . $next_month . '/';
            $button_args['title'] = ('back' == $direction) ? 'Select to move back to ' . self::Get_Month_As_String($prev_month) . ' ' . $prev_year . '.' : 'Select to move ahead to ' . self::Get_Month_As_String($next_month) . ' ' . $next_year . '.';
            $button_args['data-ajax_target'] = (isset($button_args['data-ajax_target']) && strlen($button_args['data-ajax_target'])) ? $button_args['data-ajax_target'] : '';
            $button_args['data-ajax_call_before'] = (isset($button_args['data-ajax_call_before']) && strlen($button_args['data-ajax_call_before'])) ? $button_args['data-ajax_call_before'] : '';
            $button_args['data-ajax_call_after'] = (isset($button_args['data-ajax_call_after']) && strlen($button_args['data-ajax_call_after'])) ? $button_args['data-ajax_call_after'] : '';
        }

        $result = HTML_span::Get_HTML($button_label, ['class' => 'nav_button ' . $direction]);

        if (isset($button_args['href']))
        {
            $result = HTML_a::Get_HTML($result, $button_args);
        }

        return HTML_div::Get_HTML($result, ['class' => 'cal_nav_button cal_nav_' . $direction . '_button']);
    }

    public function Get_Weekday_Labels_HTML()
    {
        return HTML_div::Get_HTML(HTML_div::Get_HTML(HTML_span::Get_HTML('SUN'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('MON'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('TUE'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('WED'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('THU'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('FRI'),
                ['class' => 'weekday_label']) . HTML_div::Get_HTML(HTML_span::Get_HTML('SAT'),
                ['class' => 'weekday_label']), ['class' => 'column_labels clearfix']);
    }

    public function Past_Date($day, $month, $year)
    {
        return ($year < self::Get_This_Year() || ($year == self::Get_This_Year() && ($month < self::Get_This_Month() || ($month == self::Get_This_Month() && $day < self::Get_This_Day()))));
    }

    public function Valid_Date($day, $month, $year)
    {
        return checkdate($month, $day, $year);
    }

    public function Get_Month_Start_Weekday($year, $month)
    {
        if (1 == strlen($month))
        {
            $month = '0' . $month;
        }

        $target = new DateTime();
        $target->setDate($year, $month, 1);

        return date_format($target, 'w');
    }

    public function Get_Month_End_Weekday($year, $month)
    {
        if (1 == strlen($month))
        {
            $month = '0' . $month;
        }

        $target = new DateTime();
        $target->setDate($year, $month, self::Get_Days_In_Month($year, $month));

        return date_format($target, 'w');
    }

    private function Build_Cell($post_to, $year = null, $month = null, $day = null, $events = null, $class = null)
    {
        $content = '';

        if ($events && 0 < count($events))
        {
            $content .= HTML_span::Get_HTML(HTML_span::Get_HTML(count($events), ['class' => 'count']) . ' class' . ((count($events) > 1) ? 'es' : ''), ['class' => 'event']);
        }

        $class = ((strlen($class)) ? $class . ' ' : '') . ((self::Past_Date($day, $month, $year)) ? 'past_date' : '');

        $today_HTML = (stristr($class, 'today')) ? HTML_span::Get_HTML(HTML_span::Get_HTML('Today'), ['class' => 'today_marker']) : '';

        return HTML_a::Get_HTML(HTML_span::Get_HTML($day, ['class' => 'label']) . HTML_span::Get_HTML($content, ['class' => 'content']) . $today_HTML, [
            'href'             => ((0 < count($events)) ? $post_to . $year . '/' . $month . '/' . $day . '/' : '#'),
            'class'            => 'day' . ((0 < strlen($content)) ? ' has_content' : '') . (($GLOBALS['ajax_on']) ? ' ajax' : '') . ((strlen($class)) ? ' ' . $class : ''),
            'data-ajax_target' => (($GLOBALS['ajax_on']) ? 'popup_content_0' : ''),
        ]);
    }
}