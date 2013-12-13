<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tool_lib
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    function get_icon ($weather_string) {

        $icon_type_01 = 'icon_weather_01.png';
        $icon_type_02 ='icon_weather_02.png';
        $icon_type_03 ='icon_weather_03.png';
        $icon_type_04 ='icon_weather_04.png';
        $icon_type_05 ='icon_weather_05.png';
        $icon_type_06 ='icon_weather_06.png';
        $icon_type_07 ='icon_weather_07.png';
        $icon_type_08 ='icon_weather_08.png';
        $icon_type_09 ='icon_weather_09.png';
        $icon_type_10 ='icon_weather_10.png';
        $icon_type_11 ='icon_weather_11.png';
        $icon_type_12 ='icon_weather_12.png';
        $icon_type_13 ='icon_weather_13.png';
        $icon_type_14 ='icon_weather_14.png';
        $icon_type_15 ='icon_weather_15.png';
        $icon_type_16 ='icon_weather_16.png';
        $icon_type_17 ='icon_weather_17.png';
        $icon_type_18 ='icon_weather_18.png';
        $icon_type_19 ='icon_weather_19.png';
        $icon_type_20 ='icon_weather_20.png';
        $icon_type_21 ='icon_weather_21.png';
        $icon_type_22 ='icon_weather_22.png';
        $icon_type_23 ='icon_weather_23.png';
        $icon_type_24 ='icon_weather_24.png';
        $icon_type_25 ='icon_weather_25.png';
        $icon_type_26 ='icon_weather_26.png';
        $icon_type_27 ='icon_weather_27.png';
        $icon_type_28 ='icon_weather_28.png';
        $icon_type_29 ='icon_weather_29.png';
        $icon_type_30 ='icon_weather_30.png';
        $icon_type_31 ='icon_weather_31.png';
        $icon_type_32 ='icon_weather_32.png';
        $icon_type_33 ='icon_weather_33.png';
        $icon_type_34 ='icon_weather_34.png';
        $icon_type_35 ='icon_weather_35.png';
        $icon_type_36 ='icon_weather_36.png';
        $icon_type_37 ='icon_weather_37.png';
        $icon_type_38 ='icon_weather_38.png';
        $icon_type_39 ='icon_weather_39.png';
        $icon_type_40 ='icon_weather_40.png';
        $icon_type_41 ='icon_weather_41.png';
        $icon_type_42 ='icon_weather_42.png';
        $icon_type_43 ='icon_weather_43.png';
        $icon_type_44 ='icon_weather_44.png';
        $icon_type_45 ='icon_weather_45.png';
        $icon_type_46 ='icon_weather_46.png';
        $icon_type_47 ='icon_weather_47.png';
        $icon_type_48 ='icon_weather_48.png';
        $icon_type_49 ='icon_weather_49.png';
        $icon_type_50 ='icon_weather_50.png';
        $icon_type_51 ='icon_weather_51.png';
        $icon_type_52 ='icon_weather_52.png';
        $icon_type_53 ='icon_weather_53.png';
        $icon_type_54 ='icon_weather_54.png';
        $icon_type_55 ='icon_weather_55.png';
        $icon_type_56 ='icon_weather_56.png';
        $icon_type_56 ='icon_weather_56.png';
        $icon_type_57 ='icon_weather_57.png';
        $icon_type_58 ='icon_weather_58.png';
        $icon_type_59 ='icon_weather_59.png';
        $icon_type_60 ='icon_weather_60.png';
        $icon_type_61 ='icon_weather_61.png';
        $icon_type_62 ='icon_weather_62.png';
        $icon_type_63 ='icon_weather_63.png';
        $icon_type_64 ='icon_weather_64.png';
        $icon_type_65 ='icon_weather_65.png';
        $icon_type_66 ='icon_weather_66.png';


        //除去パターン
        $ng_pat1='/^一時/u';
        $ng_pat2='/^時々/u';
        $ng_pat3='/^大風/u';
        $ng_pat3_1='/^暴風/u';
        
        //基本は始まる文字で選定
        $pat1='/^快晴/u';
        $pat2='/^晴/u';
        $pat2_1='/^降水なし/u';
        
        
        $pat3='/^雨/u';
        $pat4='/^大雨/u';
        $pat5='/^霧雨/u';

        $pat6='/^曇/u';
        $pat7='/^薄曇/u';
        $pat8='/^煙霧/u';
        
        $pat9='/^雷/u';

        $pat10='/^雪/u';
        $pat10_1='/^大雪/u';
        $pat11='/^みぞれ/u';
        $pat12='/^あられ/u';
        $pat13='/^雹/u';
        
        $pat14='/^霧/u';

        //晴の条件
        $after_shine_pat1 = '/後晴/u';
        $after_shine_pat2 = '/後快晴/u';
        $after_shine_pat3 = '/後時々晴/u';
        $after_shine_pat4 = '/後時々快晴/u';
        $after_shine_pat5 = '/後一時晴/u';
        $after_shine_pat6 = '/後一時快晴/u';
        $after_shine_pat7 = '/後晴を伴う/u';
        $after_shine_pat8 = '/後快晴を伴う/u';
        
        $sometimes_shine_pat1 = '/時々晴/u';
        $sometimes_shine_pat2 = '/時々快晴/u';
        $sometimes_shine_pat3 = '/一時晴/u';
        $sometimes_shine_pat4 = '/一時快晴/u';
        $sometimes_shine_pat5 = '/晴を伴う/u';
        $sometimes_shine_pat6 = '/快晴を伴う/u';

        //雨の条件
        $after_rain_pat1 = '/後雨/u';
        $after_rain_pat2 = '/後大雨/u';
        $after_rain_pat3 = '/後霧雨/u';
        $after_rain_pat4 = '/後時々雨/u';
        $after_rain_pat5 = '/後時々大雨/u';
        $after_rain_pat6 = '/後時々霧雨/u';
        $after_rain_pat7 = '/後一時雨/u';
        $after_rain_pat8 = '/後一時大雨/u';
        $after_rain_pat9 = '/後一時霧雨/u';
        $after_rain_pat10 = '/後雨を伴う/u';
        $after_rain_pat11 = '/後大雨を伴う/u';
        $after_rain_pat12 = '/後霧雨を伴う/u';
        
        $sometimes_rain_pat1 = '/時々雨/u';
        $sometimes_rain_pat2 = '/時々大雨/u';
        $sometimes_rain_pat3 = '/時々霧雨/u';
        $sometimes_rain_pat4 = '/一時雨/u';
        $sometimes_rain_pat5 = '/一時大雨/u';
        $sometimes_rain_pat6 = '/一時霧雨/u';
        $sometimes_rain_pat7 = '/雨を伴う/u';
        $sometimes_rain_pat8 = '/大雨を伴う/u';
        $sometimes_rain_pat9 = '/霧雨を伴う/u';
        
        //曇の条件
        $after_cloud_pat1 = '/後曇/u';
        $after_cloud_pat2 = '/後薄曇/u';
        $after_cloud_pat3 = '/後煙霧/u';
        $after_cloud_pat4 = '/後時々曇/u';
        $after_cloud_pat5 = '/後時々薄曇/u';
        $after_cloud_pat6 = '/後時々煙霧/u';
        $after_cloud_pat7 = '/後一時曇/u';
        $after_cloud_pat8 = '/後一時薄曇/u';
        $after_cloud_pat9 = '/後一時煙霧/u';
        $after_cloud_pat10 = '/後曇を伴う/u';
        $after_cloud_pat11 = '/後薄曇を伴う/u';
        $after_cloud_pat12 = '/後煙霧を伴う/u';
        
        $sometimes_cloud_pat1 = '/時々曇/u';
        $sometimes_cloud_pat2 = '/時々薄曇/u';
        $sometimes_cloud_pat3 = '/時々煙霧/u';
        $sometimes_cloud_pat4 = '/一時曇/u';
        $sometimes_cloud_pat5 = '/一時薄曇/u';
        $sometimes_cloud_pat6 = '/一時煙霧/u';
        $sometimes_cloud_pat7 = '/曇を伴う/u';
        $sometimes_cloud_pat8 = '/薄曇を伴う/u';
        $sometimes_cloud_pat9 = '/煙霧を伴う/u';

        //雷の条件
        $after_thunder_pat1 = '/後雷/u';
        $after_thunder_pat2 = '/後時々雷/u';
        $after_thunder_pat3 = '/後一時雷/u';
        $after_thunder_pat4 = '/後雷を伴う/u';
        
        $sometimes_thunder_pat1 = '/時々雷/u';
        $sometimes_thunder_pat2 = '/一時雷/u';
        $sometimes_thunder_pat3 = '/雷を伴う/u';

        //雪の条件
        $after_snow_pat1 = '/後雪/u';
        $after_snow_pat2 = '/後大雪/u';
        $after_snow_pat3 = '/後みぞれ/u';
        $after_snow_pat4 = '/後あられ/u';
        $after_snow_pat5 = '/後雹/u';
        
        $after_snow_pat6 = '/後時々雪/u';
        $after_snow_pat7 = '/後時々大雪/u';
        $after_snow_pat8 = '/後時々みぞれ/u';
        $after_snow_pat9 = '/後時々あられ/u';
        $after_snow_pat10 = '/後時々雹/u';
        
        $after_snow_pat11 = '/後一時雪/u';
        $after_snow_pat12 = '/後一時大雪/u';
        $after_snow_pat13 = '/後一時みぞれ/u';
        $after_snow_pat14 = '/後一時あられ/u';
        $after_snow_pat15 = '/後一時雹/u';
        
        $after_snow_pat16 = '/後雪を伴う/u';
        $after_snow_pat17 = '/後大雪を伴う/u';
        $after_snow_pat18 = '/後みぞれを伴う/u';
        $after_snow_pat19 = '/後あられを伴う/u';
        $after_snow_pat20 = '/後雹を伴う/u';

        $sometimes_snow_pat1 = '/時々雪/u';
        $sometimes_snow_pat2 = '/時々大雪/u';
        $sometimes_snow_pat3 = '/時々みぞれ/u';
        $sometimes_snow_pat4 = '/時々あられ/u';
        $sometimes_snow_pat5 = '/時々雹/u';
        
        $sometimes_snow_pat6 = '/一時雪/u';
        $sometimes_snow_pat7 = '/一時大雪/u';
        $sometimes_snow_pat8 = '/一時みぞれ/u';
        $sometimes_snow_pat9 = '/一時あられ/u';
        $sometimes_snow_pat10 = '/一時雹/u';
        
        $sometimes_snow_pat11 = '/雪を伴う/u';
        $sometimes_snow_pat12 = '/大雪を伴う/u';
        $sometimes_snow_pat13 = '/みぞれを伴う/u';
        $sometimes_snow_pat14 = '/あられを伴う/u';
        $sometimes_snow_pat15 = '/雹を伴う/u';

        //霧の条件
        $after_mist_pat1 = '/後霧/u';
        $after_mist_pat2 = '/後時々霧/u';
        $after_mist_pat3 = '/後一時霧/u';
        $after_mist_pat4 = '/後霧を伴う/u';
        
        $sometimes_mist_pat1 = '/時々霧/u';
        $sometimes_mist_pat2 = '/一時霧/u';
        $sometimes_mist_pat3 = '/霧を伴う/u';

        //ng確認
        if(preg_match($ng_pat1, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat2, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat3, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat3_1, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));

        //晴グループ
        if(preg_match($pat1, $weather_string) || preg_match($pat2, $weather_string) || preg_match($pat2_1, $weather_string)){
            //後グループ
            if( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_32;//晴後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_33;//晴後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_35;//晴後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_34;//晴後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_36;//晴後霧
            //時々グループ
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_07;//晴時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_08;//晴時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_10;//晴時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_09;//晴時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_11;//晴時々霧
            }
            return $icon_type_01;//晴
        }

        //雨グループ
        if(preg_match($pat3, $weather_string) || preg_match($pat4, $weather_string) || preg_match($pat5, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_37;//雨後晴
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_38;//雨後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_40;//雨後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_39;//雨後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_41;//雨後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_12;//雨時々晴
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_13;//雨時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_15;//雨時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_14;//雨時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_16;//雨時々霧
            }
            return $icon_type_02;//雨
        }

        //曇グループ
        if(preg_match($pat6, $weather_string) || preg_match($pat7, $weather_string) || preg_match($pat8, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_42;//曇後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_43;//曇後雨
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_45;//曇後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_44;//曇後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_46;//曇後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_17;//曇時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_18;//曇時々雨
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_20;//曇時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_19;//曇時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_21;//曇時々霧
            }
            return $icon_type_03;//曇
        }

        //雷グループ
        if(preg_match($pat9, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_52;//雷後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_53;//雷後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_55;//雷後曇
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_54;//雷後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_56;//雷後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_27;//雷時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_28;//雷時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_30;//雷時々曇
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_29;//雷時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_31;//雷時々霧
            }
            return $icon_type_04;//雷
        }

        //雪グループ
        if(preg_match($pat10, $weather_string) || preg_match($pat10_1, $weather_string) || preg_match($pat11, $weather_string) || preg_match($pat12, $weather_string) || preg_match($pat13, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_47;//雪後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_48;//雪後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_50;//雪後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_49;//雪後雷
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_51;//雪後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_22;//雪時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_23;//雪時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_25;//雪時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_24;//雪時々雷
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_26;//雪時々霧
            }
            return $icon_type_05;//雪
        }

        //霧グループ
        if(preg_match($pat14, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_62;//霧後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_63;//霧後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_64;//霧後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_66;//霧後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_65;//霧後雪
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_57;//霧時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_58;//霧時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_60;//霧時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_59;//霧時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_61;//霧時々
            }
            return $icon_type_06;//霧
        }
        return FALSE;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */