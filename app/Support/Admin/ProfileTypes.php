<?php

namespace App\Support\Admin;

use App\Models\Admin\BrokerProfile;
use App\Models\Admin\BusinessProfile;
use App\Models\Admin\IncubatorProfile;
use App\Models\Admin\InvestorProfile;
use App\Models\Admin\LenderProfile;
use App\Models\Admin\MentorProfile;
use App\Models\Admin\StartupProfile;
use App\Models\BusinessImage;
use App\Models\ProfileBusinessMgmt;
use App\Models\ProfileStartupMgmt;
use App\Models\StartupImage;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes each legacy profile table so a single admin controller can
 * list, review and moderate all of them.
 */
class ProfileTypes
{
    public const STATUS_INACTIVE = 0;

    public const STATUS_ACTIVE = 1;

    public const STATUS_PENDING = 2;

    public const STATUS_AWAITING = 3;

    public const STATUS_REJECTED = 4;

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'business' => [
                'id' => 1,
                'label' => 'Business',
                'model' => BusinessProfile::class,
                'status' => 'business_profile_status',
                'code' => 'business_profile_str',
                'name' => 'seller_name',
                'email' => 'seller_email',
                'mobile' => 'seller_mobile',
                'company' => 'seller_company',
                'headline' => 'advmt_headline',
                'city' => 'ofc_city',
                'image' => 'seller_prof_pic',
                'search' => ['seller_name', 'seller_email', 'seller_mobile', 'seller_company', 'business_profile_str', 'advmt_headline'],
                'editable' => [
                    'seller_name' => 'Name', 'seller_email' => 'Email', 'seller_mobile' => 'Mobile',
                    'seller_designation' => 'Designation', 'seller_company' => 'Company',
                    'advmt_headline' => 'Headline', 'seller_intro' => 'Introduction',
                    'business_website' => 'Website', 'ofc_city' => 'City', 'ofc_state' => 'State',
                    'ofc_country' => 'Country', 'company_summary' => 'Company Summary',
                ],
                'images' => [
                    'model' => BusinessImage::class,
                    'foreign_key' => 'business_id',
                    'path_column' => 'business_img_path',
                    'name_column' => 'business_img_name',
                    'folder' => 'business-profiles',
                ],
                'management' => [
                    'model' => ProfileBusinessMgmt::class,
                    'foreign_key' => 'business_profile_id',
                ],
            ],
            'startup' => [
                'id' => 7,
                'label' => 'Startup',
                'model' => StartupProfile::class,
                'status' => 'startup_profile_status',
                'code' => 'startup_profile_str',
                'name' => 'startup_name',
                'email' => 'startup_email',
                'mobile' => 'startup_mobile',
                'company' => 'name_of_entity',
                'headline' => 'advmt_headline',
                'city' => 'ofc_city',
                'image' => 'startup_prof_pic',
                'search' => ['startup_name', 'startup_email', 'startup_mobile', 'name_of_entity', 'startup_profile_str', 'advmt_headline'],
                'editable' => [
                    'startup_name' => 'Name', 'startup_email' => 'Email', 'startup_mobile' => 'Mobile',
                    'startup_designation' => 'Designation', 'name_of_entity' => 'Entity Name',
                    'advmt_headline' => 'Headline', 'startup_intro' => 'Introduction',
                    'business_website' => 'Website', 'ofc_city' => 'City', 'ofc_state' => 'State',
                    'ofc_country' => 'Country', 'company_summary' => 'Company Summary',
                ],
                'images' => [
                    'model' => StartupImage::class,
                    'foreign_key' => 'startup_id',
                    'path_column' => 'startup_img_path',
                    'name_column' => 'startup_img_name',
                    'folder' => 'startup-profiles',
                ],
                'management' => [
                    'model' => ProfileStartupMgmt::class,
                    'foreign_key' => 'startup_profile_id',
                ],
            ],
            'investor' => [
                'id' => 2,
                'label' => 'Investor',
                'model' => InvestorProfile::class,
                'status' => 'inv_profile_status',
                'code' => 'inv_profile_str',
                'name' => 'inv_name',
                'email' => 'inv_email',
                'mobile' => 'inv_mobile',
                'company' => 'company_name',
                'headline' => 'inv_headline',
                'city' => 'inv_city',
                'image' => 'inv_profile_pic_path',
                'search' => ['inv_name', 'inv_email', 'inv_mobile', 'company_name', 'inv_profile_str', 'inv_headline'],
                'editable' => [
                    'inv_name' => 'Name', 'inv_email' => 'Email', 'inv_mobile' => 'Mobile',
                    'inv_headline' => 'Headline', 'inv_intro' => 'Introduction',
                    'company_name' => 'Company', 'company_designation' => 'Designation',
                    'company_website' => 'Website', 'inv_city' => 'City', 'inv_state' => 'State',
                    'inv_country' => 'Country', 'company_summary' => 'Company Summary',
                ],
            ],
            'mentor' => [
                'id' => 4,
                'label' => 'Mentor',
                'model' => MentorProfile::class,
                'status' => 'mentor_profile_status',
                'code' => 'mentor_profile_str',
                'name' => 'mentor_name',
                'email' => 'mentor_email',
                'mobile' => 'mentor_mobile',
                'company' => 'mentor_company',
                'headline' => 'mentor_adv_headline',
                'city' => 'mentor_city',
                'image' => 'mentor_profile_pic',
                'search' => ['mentor_name', 'mentor_email', 'mentor_mobile', 'mentor_company', 'mentor_profile_str', 'mentor_adv_headline'],
                'editable' => [
                    'mentor_name' => 'Name', 'mentor_email' => 'Email', 'mentor_mobile' => 'Mobile',
                    'mentor_adv_headline' => 'Headline', 'mentor_intro' => 'Introduction',
                    'mentor_company' => 'Company', 'mentor_designation' => 'Designation',
                    'mentor_linkedin' => 'LinkedIn', 'mentor_city' => 'City', 'mentor_state' => 'State',
                    'mentor_country' => 'Country', 'mentor_profile_summary' => 'Profile Summary',
                ],
            ],
            'lender' => [
                'id' => 3,
                'label' => 'Lender',
                'model' => LenderProfile::class,
                'status' => 'lender_profile_status',
                'code' => 'lender_profile_str',
                'name' => 'lender_name',
                'email' => 'lender_email',
                'mobile' => 'lender_mobile',
                'company' => 'nbfc_comp_name',
                'headline' => 'lender_adv_headline',
                'city' => 'lender_city',
                'image' => 'profile_pic_path',
                'search' => ['lender_name', 'lender_email', 'lender_mobile', 'nbfc_comp_name', 'lender_profile_str', 'lender_adv_headline'],
                'editable' => [
                    'lender_name' => 'Name', 'lender_email' => 'Email', 'lender_mobile' => 'Mobile',
                    'lender_adv_headline' => 'Headline', 'lender_intro' => 'Introduction',
                    'nbfc_comp_name' => 'Company', 'nbfc_website' => 'Website',
                    'lender_city' => 'City', 'lender_state' => 'State', 'lender_country' => 'Country',
                    'prof_summary' => 'Profile Summary',
                ],
            ],
            'incubator' => [
                'id' => 5,
                'label' => 'Incubator',
                'model' => IncubatorProfile::class,
                'status' => 'incubator_profile_status',
                'code' => 'incubator_profile_str',
                'name' => 'incubator_name',
                'email' => 'incubator_email',
                'mobile' => 'incubator_mobile',
                'company' => 'incubator_company',
                'headline' => 'incubator_adv_headline',
                'city' => 'incubator_city',
                'image' => 'incubator_company_logo',
                'search' => ['incubator_name', 'incubator_email', 'incubator_mobile', 'incubator_company', 'incubator_profile_str', 'incubator_adv_headline'],
                'editable' => [
                    'incubator_name' => 'Name', 'incubator_email' => 'Email', 'incubator_mobile' => 'Mobile',
                    'incubator_adv_headline' => 'Headline', 'incubator_intro' => 'Introduction',
                    'incubator_company' => 'Company', 'incubator_designation' => 'Designation',
                    'company_website' => 'Website', 'incubator_city' => 'City', 'incubator_state' => 'State',
                    'incubator_country' => 'Country', 'incubator_profile_summary' => 'Profile Summary',
                ],
            ],
            'broker' => [
                'id' => 6,
                'label' => 'Broker',
                'model' => BrokerProfile::class,
                'status' => 'broker_profile_status',
                'code' => 'broker_profile_str',
                'name' => 'broker_name',
                'email' => 'broker_email',
                'mobile' => 'broker_mobile',
                'company' => 'broker_company',
                'headline' => 'broker_company',
                'city' => 'company_city',
                'image' => 'broker_company_logo',
                'search' => ['broker_name', 'broker_email', 'broker_mobile', 'broker_company', 'broker_profile_str'],
                'editable' => [
                    'broker_name' => 'Name', 'broker_email' => 'Email', 'broker_mobile' => 'Mobile',
                    'broker_company' => 'Company', 'company_website' => 'Website',
                    'company_city' => 'City', 'company_state' => 'State', 'company_country' => 'Country',
                    'prof_summary' => 'Profile Summary',
                ],
            ],
        ];
    }

    public static function slugs(): array
    {
        return array_keys(self::all());
    }

    /**
     * @return array<string, mixed>
     */
    public static function get(string $slug): array
    {
        $type = self::all()[$slug] ?? null;

        abort_if($type === null, 404);

        return $type + ['slug' => $slug];
    }

    public static function query(string $slug)
    {
        /** @var class-string<Model> $model */
        $model = self::get($slug)['model'];

        return $model::query();
    }

    public static function slugForId(int $typeId): ?string
    {
        foreach (self::all() as $slug => $type) {
            if ($type['id'] === $typeId) {
                return $slug;
            }
        }

        return null;
    }

    /**
     * Status filter tabs shown on profile listings.
     *
     * @return array<string, array{label: string, statuses: array<int>}>
     */
    public static function statusTabs(): array
    {
        return [
            'pending' => ['label' => 'Pending', 'statuses' => [self::STATUS_PENDING, self::STATUS_AWAITING]],
            'active' => ['label' => 'Active', 'statuses' => [self::STATUS_ACTIVE]],
            'inactive' => ['label' => 'Inactive', 'statuses' => [self::STATUS_INACTIVE]],
            'rejected' => ['label' => 'Rejected', 'statuses' => [self::STATUS_REJECTED]],
            'all' => ['label' => 'All', 'statuses' => []],
        ];
    }
}
