<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;

/**
 * Want to stick out in a good way?
 * Instead of sending us a traditional resume send us your resume through our resume API
 * Send a PUT request to https://api.filmsupply.com/developer-resumes
 * The body of the request should be in the format shown here https://jsonresume.org/schema/
 * Below is the Laravel custom form request that will validate your request
 *
 * Just some of the many benefits of working at Filmsupply
 * - Consistent work hours. Work supports the family, not the other way around. Long hours and weekend work is rare.
 * - Health / Vision / Dental insurance
 * - 401K with employer matching
 * - 15 days PTO per year, plus sick days, plus maternity and paternity leave
 * - Free training resources. You also get your own training budget per year.
 * - Company issued top of the line Macbook Pro
 * - Free software
 *
 * Class DeveloperResumeRequest
 * @package App\Http\Requests
 */
class DeveloperResumeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array($this->header('Authorization'), array_keys(self::getAuthTokens()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'basics' => 'required|array',
            'basics.name' => 'required|string',
            'basics.email' => 'required|email',
            'basics.picture' => 'url',
            'basics.website' => 'url',
            'basics.profiles' => 'array',
            'work' => 'required|array',
            'skills' => 'required|array',
        ];

        return array_merge($rules, $this->workRules(), $this->skillsRules(), $this->profilesRules());
    }

    /**
     * Gets an array of auth tokens and the hiring source they correspond to.
     * Pick the source of how you found this and use the correct auth token.
     *
     * @return array
     */
    public static function getAuthTokens()
    {
        return [
            'DeveloperResumeIndeed2019' => 'Indeed',
            '2019LaraJobsResume' => 'LaraJobs',
            'WebsiteAuthDevResume2019' => 'Website',
            '2019FriendReferralDevResume' => 'Friend Referral',
            'WebsiteConsole2019DevResume' => 'Website Console',
            'Whatever2019DevResume' => 'Other',
        ];
    }

    /**
     * Looking for application/json content type
     *
     * @return boolean
     */
    public function wantsJson()
    {
        return true;
    }

    /**
     * Unfortunately Laravel 5.1 does not have '*' nested array validators.
     * So we need to manually create the rules for each array entry
     *
     * @return array
     */
    protected function workRules()
    {
        $rules = [];
        if ($workData = $this->get('work')) {
            foreach ($workData as $index => $data) {
                $rules['work.' . $index . '.company'] = 'required|string';
                $rules['work.' . $index . '.position'] = 'required|string';
                $rules['work.' . $index . '.website'] = 'url';
                $rules['work.' . $index . '.startDate'] = 'required|date_format:Y-m-d';
                $rules['work.' . $index . '.endDate'] = 'date_format:Y-m-d';
                $rules['work.' . $index . '.summary'] = 'string';
                $rules['work.' . $index . '.highlights'] = 'required|array';
            }
        }

        return $rules;
    }

    /**
     * Unfortunately Laravel 5.1 does not have '*' nested array validators.
     * So we need to manually create the rules for each array entry
     *
     * @return array
     */
    protected function skillsRules()
    {
        $rules = [];
        if ($skillsData = $this->get('skills')) {
            foreach ($skillsData as $index => $data) {
                $rules['skills.' . $index . '.name'] = 'required|string';
                $rules['skills.' . $index . '.level'] = 'required|string';
                $rules['skills.' . $index . '.keywords'] = 'required|array';
            }
        }

        return $rules;
    }

    /**
     * Unfortunately Laravel 5.1 does not have '*' nested array validators.
     * So we need to manually create the rules for each array entry
     *
     * @return array
     */
    protected function profilesRules()
    {
        $rules = [];
        if ($profileData = $this->get('basics.profiles')) {
            foreach ($profileData as $index => $profile) {
                $rules['basics.profiles.' . $index . '.network'] = 'required|string';
                $rules['basics.profiles.' . $index . '.username'] = 'string';
                $rules['basics.profiles.' . $index . '.url'] = 'required|url';
            }
        }

        return $rules;
    }
}
