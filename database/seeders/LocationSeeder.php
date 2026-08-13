<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Deactivate existing locations
        Location::query()->update(['is_active' => false]);
        
        $locations = [
            ['code' => '0001', 'name' => 'Head Office', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0010', 'name' => 'Diredawa - Federal Prison Administration', 'type' => 'project', 'is_active' => true],
            ['code' => '0019', 'name' => 'Ambo University WWT', 'type' => 'project', 'is_active' => true],
            ['code' => '0020', 'name' => 'Ayat 40/60 Condominium', 'type' => 'project', 'is_active' => true],
            ['code' => '0027', 'name' => 'Mettu Expansion', 'type' => 'project', 'is_active' => true],
            ['code' => '0028', 'name' => 'Chiro Infrastructure', 'type' => 'project', 'is_active' => true],
            ['code' => '0029', 'name' => 'TVET Residential Building /ቲቪቲ የመኖርያ ህንፃ', 'type' => 'project', 'is_active' => true],
            ['code' => '0030', 'name' => 'CP Cadila Pharmaceuticals', 'type' => 'project', 'is_active' => true],
            ['code' => '0032', 'name' => 'EPRDF Head Office Building', 'type' => 'project', 'is_active' => true],
            ['code' => '0038', 'name' => 'Ambo Stadium', 'type' => 'project', 'is_active' => true],
            ['code' => '0039', 'name' => 'Arbaminch Abaya & Chamo', 'type' => 'project', 'is_active' => true],
            ['code' => '0041', 'name' => 'Chiro Building Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0043', 'name' => 'Bole Lemi Water Supply', 'type' => 'project', 'is_active' => true],
            ['code' => '0044', 'name' => 'AASTU-Commercial Building', 'type' => 'project', 'is_active' => true],
            ['code' => '0046', 'name' => 'Diredawa Waste Water', 'type' => 'project', 'is_active' => true],
            ['code' => '0047', 'name' => 'Kentiba W/Tsadik Green Park', 'type' => 'project', 'is_active' => true],
            ['code' => '0049', 'name' => 'Ambo Teaching & Referral Hospital Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0050', 'name' => 'Arbaminch UG & PG Class Room', 'type' => 'project', 'is_active' => true],
            ['code' => '0052', 'name' => 'Bulbula Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0054', 'name' => 'Bensa Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0055', 'name' => 'Dilla Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0056', 'name' => 'Shashemene Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0057', 'name' => 'Yirgachefe WWTP', 'type' => 'project', 'is_active' => true],
            ['code' => '0058', 'name' => 'Augusta Weyra Junction Road Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0060', 'name' => 'Zambiya Embassy Chancery Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0061', 'name' => 'Debre Birhan Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0062', 'name' => 'Bahirdar Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0063', 'name' => 'Debre Markos University Teaching Hospital', 'type' => 'project', 'is_active' => true],
            ['code' => '0064', 'name' => 'Meki Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0065', 'name' => 'Bale Robe Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0066', 'name' => 'Ethiopian Enviro & Forest Rese', 'type' => 'project', 'is_active' => true],
            ['code' => '0067', 'name' => 'Ethiopian Sugar Corporation Head Quarter Building', 'type' => 'project', 'is_active' => true],
            ['code' => '0068', 'name' => 'Debrebirhan University Class Room & Computing School A', 'type' => 'project', 'is_active' => true],
            ['code' => '0069', 'name' => 'Arbaminch University Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0070', 'name' => 'AACGRB Kechene & Shegole', 'type' => 'project', 'is_active' => true],
            ['code' => '0071', 'name' => 'Addis Ababa Small & Medium Manufacturing Industry(Mega project)', 'type' => 'project', 'is_active' => true],
            ['code' => '0072', 'name' => 'Bonga Waste Water Treatment Plant', 'type' => 'project', 'is_active' => true],
            ['code' => '0074', 'name' => 'City Administration of Addis Ababa Women Rehabilitation & Skill', 'type' => 'project', 'is_active' => true],
            ['code' => '0075', 'name' => 'Chiro UniversityTwo Seminar Halls & Two L Phase 3', 'type' => 'project', 'is_active' => true],
            ['code' => '0076', 'name' => 'Head Office - One', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0077', 'name' => 'Head Office -RW', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0078', 'name' => 'Head Office - Charity', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0079', 'name' => 'Head Office-Wereda 6', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0080', 'name' => 'Equipment Administration Store - Head Office', 'type' => 'store', 'is_active' => true],
            ['code' => '0081', 'name' => 'Ambo University DB WWTP', 'type' => 'project', 'is_active' => true],
            ['code' => '0082', 'name' => 'Chiro Oda Bultum University Main Gate Access Road & Bridge Struc', 'type' => 'project', 'is_active' => true],
            ['code' => '0083', 'name' => 'Head Office-4killo Meskerem School', 'type' => 'head_office', 'is_active' => true],
            ['code' => '0084', 'name' => 'KOLFE KERANIYO GENERAL HOSPITAL PROJECT LOT 1', 'type' => 'project', 'is_active' => true],
            ['code' => '0085', 'name' => 'NEFAS SILK GENERAL HOSPITAL PROJECT LOT 1', 'type' => 'project', 'is_active' => true],
            ['code' => '0086', 'name' => 'Addis Ababa Corridor Dev\'t Project-Tewdros Roundabout Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0087', 'name' => 'Addis Ababa Corridor Dev\'t Project- Basha Wolde Chilot Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0088', 'name' => 'Addis Ababa Corridor Dev\'t Project- Mexico Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0089', 'name' => 'Addis Ababa Corridor Dev\'t Project- Bole Noc Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0090', 'name' => 'Addis Ababa Corridor Dev\'t Project- Megenagna Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0091', 'name' => 'Addis Ababa Corridor Dev\'t Project- Bole Japan Embassy Parking', 'type' => 'project', 'is_active' => true],
            ['code' => '0092', 'name' => 'Addis Ababa Lideta Charity Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0093', 'name' => 'KOLFE KERANIYO GENERAL HOSPITAL PROJECT LOT 2', 'type' => 'project', 'is_active' => true],
            ['code' => '0094', 'name' => 'NEFAS SILK GENERAL HOSPITAL PROJECT LOT 2', 'type' => 'project', 'is_active' => true],
            ['code' => '0095', 'name' => 'Entoto to Pickock Park', 'type' => 'project', 'is_active' => true],
            ['code' => '0096', 'name' => 'ETH AirLines Bishoftu 340 Villa 3A', 'type' => 'project', 'is_active' => true],
            ['code' => '0097', 'name' => 'ETH AirLines Bishoftu 340 Villa 3B', 'type' => 'project', 'is_active' => true],
            ['code' => '0098', 'name' => 'ETH AirLines Bishoftu 340 Villa 3C', 'type' => 'project', 'is_active' => true],
            ['code' => '0099', 'name' => 'ETH AirLines Bishoftu 340 Villa 3D', 'type' => 'project', 'is_active' => true],
            ['code' => '0100', 'name' => 'ETH AirLines Bishoftu 340 Villa 3E', 'type' => 'project', 'is_active' => true],
            ['code' => '0101', 'name' => 'FDRE Skills Development Park Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0102', 'name' => 'ETH AirLines Bishoftu 340 Villa Central', 'type' => 'project', 'is_active' => true],
            ['code' => '0103', 'name' => 'Addis Ababa around Amasader Area', 'type' => 'project', 'is_active' => true],
            ['code' => '0104', 'name' => 'Chaina 00-Meketeya Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0105', 'name' => 'Guto Meda Korea Park Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0106', 'name' => 'Kechene Menen Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0107', 'name' => 'Ambasder Park Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0108', 'name' => 'Bambis Bridge Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0109', 'name' => 'Kebena Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0110', 'name' => 'PetrosePawelose Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0111', 'name' => 'Fileweha Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0112', 'name' => 'Peacock commercial building Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0113', 'name' => 'Peacock Riverside Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0114', 'name' => 'EAU South Campus፡ Lot-1', 'type' => 'project', 'is_active' => true],
            ['code' => '0115', 'name' => 'ETH Airlines -Bole Project', 'type' => 'project', 'is_active' => true],
            ['code' => '0116', 'name' => 'Sub @ Bishoftu Int. Airport', 'type' => 'project', 'is_active' => true],
            ['code' => '0117', 'name' => 'Tewodros Round', 'type' => 'project', 'is_active' => true],
            ['code' => '0118', 'name' => 'DESIGN AND BUILD OF ANRS PRESIDENT OFFICE BUILDING PROJECT', 'type' => 'project', 'is_active' => true],
            ['code' => '0119', 'name' => 'G+8 residential building', 'type' => 'project', 'is_active' => true],
            ['code' => 'MAIN', 'name' => 'MAIN STORE', 'type' => 'store', 'is_active' => true],
            ['code' => 'SIT', 'name' => 'STOCK IN TRANSFER', 'type' => 'store', 'is_active' => true],
        ];
        
        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['code' => $location['code']],
                [
                    'name' => $location['name'],
                    'type' => $location['type'],
                    'is_active' => $location['is_active'],
                ]
            );
        }
        
        echo "✓ " . count($locations) . " locations added successfully!\n";
    }
}
