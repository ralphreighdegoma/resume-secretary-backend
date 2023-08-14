<?php 
namespace App\Actions;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Chat
{
    use AsAction;

    public function handle(Request $request)
    {
        //call api via guzzle client via api and add Authorization bearer token and instance id
        $hidden_word = $this->getHiddenWordFromDb();
        $client = new Client();
        $system = $this->myInfo();

        $data = [
            "model" =>  "gpt-3.5-turbo",
            "max_tokens" => 100,
            "temperature" => 0.2,
            "messages"=> [["role"=> "system", "content"=> $system], ["role"=> "user", "content"=>  $request->input]],
            "n"=> 1

        ];

        $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer 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',
                'Content-Type' => 'application/json'
            ],
        ]);

        $response =  $response->getBody();
        $data = $response->getContents();
        $formatted =  json_decode($data);

        $message = $formatted->choices[0]->message->content;

        return response()->json([
            'message' => $message
        ], 200);

    }

    //get word from hidden_words table that id is not in solved table
    public function getHiddenWordFromDb()
    {
        $word = \App\Models\HiddenWord::whereNotIn('id', function($query){
            $query->select('word_id')->from('solved');
        })->inRandomOrder()->first();

        return $word;
    }

    //save solved word to solved table
    public function saveSolvetToDb($word, $name = "name", $email = "email", $phone = "address")
    {
        $solved = new \App\Models\Solved;
        $solved->word_id = $word->id;
        $solved->name = $name;
        $solved->email = $email;
        $solved->phone = $phone;
        $solved->save();
    }

    public function myInfo()
    {
        return "

            Pretend to be ralph reigh degoma, that's it. just respond anything about ralph reigh degoma.

            RALPH REIGH G. DEGOMA LARAVEL / PHP / ANGULARJS / VUE/ NUXT/ GIT
            Experience: 6+ years
            Address: Butuan City, Philippines
            Skype: degomaralphreigh
            Email: personal.ralphdegoma@gmail.com Phone: +639174277963
            EDUCATIONAL BACKGROUND
            ● Bachelor's Degree in Information Technology
            FATHER SATURNINO URIOS UNIVERSITY
            TECHNICAL SKILLS
            ● LAMP Stack
            ● Laravel (5.5>)
            ● Php OOP
            ● Legacy softwares experience
            ● Angularjs, Javascript , Jquery, Vuejs, Nuxtjs
            ● Can work well on both Linux and Windows environment
            ● Git management
            ● Redis
            ● Ssh, simple server commands
            PROFESSIONAL EXPERIENCE
            ❖ Loangraphs (April 2021 - present) USA - Remote Lead developer
            ● Create a web application from scratch using the latest web technologies such as Laravel and vuejs. This will be a SAAS and will be launching this year (2022).
            ● API-based web application to be used by loan officers.
            Skills used: Laravel, Vuejs, Html, Css ,Git, Sendgrid, Nuxtjs Website link : https://loangraphs.com
            
            ❖ Creator Galaxy (January 2020 - March - 2021) Germany - Remote Lead Developer
            ● An ecommerce website that focuses on selling digital products for filmmakers and videographers.
            ● Uses Laravel and Vuejs for the frontend.
            ● Creation of backend reports, admin portal and Stripe Payment implementation.
            ● Optimization
            Skills used: Laravel, Vuejs, Html, Css ,Git, Memcache Website link : https://creatorgalaxy.com
            ❖ Conversant Solutions (January 2019 - December 2019) Singapore - Remote Backend Developer
            ● Cms Video Streaming Service, a netflix clone. A million dollar project that is deployed in many countries like Fiji, Ecuador and Thailand. Stream videos, billing, epg , ads management and many more. It also has admin, moderator, content provider portals.
            ● Able to multitask more than 4 projects at a time, to fix bugs add features from different servers deployed.
            PROJECTS DONE
            ● https://miott.com/
            Skills used: Laravel, AngularJs, Angular, Jquery, Html, Css ,Git, Memcache Website link : https://www.conversant.tv/
            ❖ Techleus LLC (January 2018 - December 2018) San Diego California - Remote Lead Developer
            ● Working as a laravel and Angularjs developer to develop a web application to cater jobs for Anesthesiologist and Crna. The project is estimated to profit $100k / month.
            ● Lead developer, worked with 3 other web developers
            ● Create structure of Angularjs SPA routing
            ● Create Database structure
            Skills used: Laravel, Angularjs, Jquery, Html, Css , Git, Server Management Project link : www.ethesia.com
            ❖ Photoup Inc. (November 2016 - December 2017)
            Pope John Paul II Ave, Cebu City, Philippines - Office Based Jr. Developer
            ● Utilized the skills on using PHP language and Angularjs. Able to attend scrum meetings, sprint meetings and standup meetings everyday. Communication skills are honed in order to deliver and properly implement daily tasks.
            ● Create complicated reports for the company.
            ● Debug and add new features to existing php modules.
            Skills used: Php, Angularjs, Jquery, Html, Css , Git Company website: www.photoup.net
            ❖ Freelance Web Developer (March 2016 - September 2016)
            ● Using laravel as a framework, created a School Management Software to serve local schools who can’t afford expensive software companies.
            ● Lead developer, with 3 other web developers.
            Skills used: Laravel, Jquery, Html, Css , Git, Server Management
            Link free to download: https://github.com/ralphdegoma/school-system ❖ G-HOVEN I.T SOLUTIONS (May 2015 - February 2016)
            Web Developer / Visual Basic Programmer
            ● Working as a web developer to create a School Management System for a local tech vocational school.
            ● Created reports for a legacy software created by the company 15 years ago using visual basic language. Able to debug and add new reports to existing legacy software.
            Skills used: Laravel, Jquery, Html, Css , Git, Visual Basic
       ";

       
    }

}