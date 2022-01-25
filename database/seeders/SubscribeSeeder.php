<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscribeDatatable;

class SubscribeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $ip = 193;
        //SubscribeSeeder::create_row('', '', $ip++);
        $ip++; //193
        SubscribeSeeder::create_row('吳威廷', 'Cybersoft', $ip++);
        SubscribeSeeder::create_row('莊宜勳', '計劃用', $ip++);
        SubscribeSeeder::create_row('莊宜勳', 'RSC', $ip++);
        SubscribeSeeder::create_row('網路組', 'SSLVPN', $ip++);
        SubscribeSeeder::create_row('王允辰', '個人使用', $ip++);
        SubscribeSeeder::create_row('網路組', 'vCenter', $ip++);
        SubscribeSeeder::create_row('莊宜勳', '個人使用', $ip++); //200
        SubscribeSeeder::create_row('網路組', 'GitLab使用', $ip++);
        SubscribeSeeder::create_row('網路組', 'VMware管理', $ip++);
        SubscribeSeeder::create_row('網路組', '新Redmine', $ip++);
        SubscribeSeeder::create_row('范諾', '弓銓計劃固定使用', $ip++);
        SubscribeSeeder::create_row('網路組(Error)', '暫換(IPCAM)', $ip++);
        SubscribeSeeder::create_row('網路組', '2021_05新買的HPE Server', $ip++);
        SubscribeSeeder::create_row('蔡仁勝', '個人使用', $ip++);
        SubscribeSeeder::create_row('網路組', 'IPcam', $ip++);
        SubscribeSeeder::create_row('工研院老人計畫demo暫時使用', '', $ip++);
        SubscribeSeeder::create_row('范諾', '研究用', $ip++); //210
        SubscribeSeeder::create_row('網路組', 'CLOUD(Spark)(Vsphere download)', $ip++);
        SubscribeSeeder::create_row('資料組', '研究用', $ip++);
        SubscribeSeeder::create_row('資料組', '研究用', $ip++);
        SubscribeSeeder::create_row('范諾', '計畫使用', $ip++);
        SubscribeSeeder::create_row('網路組', '2021_05新買HPE伺服器對外服務IP', $ip++);
        SubscribeSeeder::create_row('網路組', 'smartlisa資料驅動智慧應用技術產學聯盟', $ip++);
        $ip++; //217
        SubscribeSeeder::create_row('江紹賢', '個人使用', $ip++);
        SubscribeSeeder::create_row('網路組', '2021_12新買的NAS', $ip++);
        SubscribeSeeder::create_row('網路組', '實驗室印表機', $ip++);
        SubscribeSeeder::create_row('網路組', '實驗室AP(ISMP_AP)', $ip++); //221
        $ip = 241;
        SubscribeSeeder::create_row('網路組', 'DAI資料驅動智慧應用技術產學聯盟', $ip++);
        SubscribeSeeder::create_row('Jacky', '計畫server用', $ip++);
        SubscribeSeeder::create_row('娟娟', '個人使用', $ip++);
        SubscribeSeeder::create_row('網路組', 'AP、Vsphere(Server1)', $ip++);
        SubscribeSeeder::create_row('網路組', 'Vsphere(Server2)', $ip++);
        SubscribeSeeder::create_row('網路組', 'dlink switch', $ip++);
        SubscribeSeeder::create_row('網路組', '實驗室NAS', $ip++);
        SubscribeSeeder::create_row('', 'IPcam', $ip++);
        SubscribeSeeder::create_row('網路組', 'FTP', $ip++);
        SubscribeSeeder::create_row('網路組', '實驗室網站', $ip++);
        SubscribeSeeder::create_row('網路組', 'credit+SSCS(NAT功能)', $ip++);
        SubscribeSeeder::create_row('網路組', 'redmine', $ip++);
        SubscribeSeeder::create_row('網路組', '中心印表機', $ip++); //253
    }

    public static function create_row($s, $u, $i)
    {
        $res = SubscribeDatatable::create([
            'subscriber' => $s,
            'usage' => $u,
            'ip' => $i,
        ]);
        
        $ipdata = $res->get_ip;
        $ipdata['current_subscriber_id'] = $res['id'];
        $ipdata->save();
    }
}
       
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            