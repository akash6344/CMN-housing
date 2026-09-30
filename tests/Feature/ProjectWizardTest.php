<?php

namespace Tests\Feature;

use App\Models\ProjectDetail;
use App\Models\UnitConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectWizardTest extends TestCase
{
    use RefreshDatabase;
    public function test_complete_project_posting_lifecycle(): void
    {
        // 1. Step 1: Save Basic Details
        $step1Response = $this->postJson(route('builder.projects.wizard.basic'), [
            'name'            => 'Skyline Luxury Towers',
            'tagline'         => 'Elevate Your Standard of Living',
            'builder_name'    => 'Prestige Developers',
            'location'        => 'Whitefield, Bangalore',
            'maps_link'       => 'https://maps.google.com/?q=Whitefield+Bangalore',
            'project_type'    => 'Residential Apartment',
            'project_status'  => 'Under Construction',
            'possession_date' => '2027-06-30',
            'rera_number'     => 'PRM/KA/RERA/1251/446/PR/200123/001234',
            'towers'          => 4,
            'total_units'     => 320,
            'land_area'       => '8.5 Acres',
            'description'     => 'A premium gated community with modern architecture and green open spaces.',
            'highlights'      => ['Next to Metro Station', '80% Open Space', 'Infinity Edge Pool'],
        ]);

        $step1Response->assertStatus(200);
        $step1Response->assertJson(['success' => true]);
        $projectId = $step1Response->json('project_id');
        $this->assertNotNull($projectId);

        // 2. Step 2: Save Units and Pricing
        $step2Response = $this->postJson(route('builder.projects.wizard.units'), [
            'project_id'            => $projectId,
            'smart_bargain_enabled' => true,
            'min_expected_price'    => 12000000,
            'target_price'          => 12800000,
            'max_price'             => 13500000,
            'units'                 => [
                [
                    'unit_type'       => '2 BHK Elite',
                    'built_up_area'   => 1250,
                    'carpet_area'     => 1020,
                    'price'           => 12500000,
                    'available_units' => 45,
                    'floor_range'     => '1st - 10th Floor',
                    'show_floor_plan' => true,
                ],
                [
                    'unit_type'       => '3 BHK Grand',
                    'built_up_area'   => 1850,
                    'carpet_area'     => 1520,
                    'price'           => 18500000,
                    'available_units' => 30,
                    'floor_range'     => '10th - 30th Floor',
                    'show_floor_plan' => true,
                ],
            ],
        ]);

        $step2Response->assertStatus(200);
        $step2Response->assertJson(['success' => true]);

        // 3. Step 3: Save Amenities
        $step3Response = $this->postJson(route('builder.projects.wizard.amenities'), [
            'project_id'    => $projectId,
            'amenities'     => ['Clubhouse', 'Swimming Pool', 'Gym', '24x7 Security'],
            'other_amenity' => 'EV Fast Charging Station',
        ]);

        $step3Response->assertStatus(200);
        $step3Response->assertJson(['success' => true]);
        $this->assertContains('EV Fast Charging Station', $step3Response->json('amenities'));

        // 4. File Uploads (Image to ProjectMedia, PDF to ProjectDocument)
        Storage::fake('public');
        $file = UploadedFile::fake()->image('elevation.jpg', 800, 600);
        $uploadPhotoResponse = $this->postJson(route('builder.projects.wizard.upload'), [
            'file'       => $file,
            'category'   => 'photos',
            'project_id' => $projectId,
        ]);
        $uploadPhotoResponse->assertStatus(200);
        $uploadPhotoResponse->assertJson(['success' => true]);
        $uploadedPhoto = $uploadPhotoResponse->json('file');

        $docFile = UploadedFile::fake()->create('brochure.pdf', 1024, 'application/pdf');
        $uploadDocResponse = $this->postJson(route('builder.projects.wizard.upload'), [
            'file'       => $docFile,
            'category'   => 'brochure',
            'project_id' => $projectId,
        ]);
        $uploadDocResponse->assertStatus(200);
        $uploadDocResponse->assertJson(['success' => true]);
        $uploadedDoc = $uploadDocResponse->json('file');

        // 5. Step 4: Save Media & Preferences
        $step4Response = $this->postJson(route('builder.projects.wizard.media'), [
            'project_id'        => $projectId,
            'video_url'         => 'https://youtube.com/watch?v=sample123',
            'virtual_tour_url'  => 'https://matterport.com/tour/sample123',
            'media_preferences' => [
                'show_gallery'     => true,
                'show_floor_plans' => true,
                'show_video'       => true,
            ],
            'media_photos'      => [$uploadedPhoto],
            'documents'         => [$uploadedDoc],
        ]);

        $step4Response->assertStatus(200);
        $step4Response->assertJson(['success' => true]);

        // 6. Step 5: Final Submission
        $submitResponse = $this->postJson(route('builder.projects.wizard.submit'), [
            'project_id'     => $projectId,
            'terms_accepted' => true,
        ]);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJson([
            'success' => true,
            'status'  => 'under_review',
        ]);

        // 7. Verify Normalized Database Records
        $project = ProjectDetail::with(['unitConfigurations', 'media', 'documents'])->find($projectId);
        $this->assertNotNull($project);
        $this->assertEquals('under_review', $project->status);
        $this->assertEquals('Skyline Luxury Towers', $project->name);
        $this->assertCount(2, $project->unitConfigurations);
        $this->assertGreaterThanOrEqual(1, $project->media()->count());
        $this->assertGreaterThanOrEqual(1, $project->documents()->count());
        $this->assertDatabaseHas('project_media', ['project_detail_id' => $projectId, 'category' => 'photos']);
        $this->assertDatabaseHas('project_documents', ['project_detail_id' => $projectId, 'category' => 'brochure']);
        $this->assertEquals('https://youtube.com/watch?v=sample123', $project->video_url);

        // 8. Verify Project appears on Projects page
        $projectsPage = $this->get(route('builder.projects'));
        $projectsPage->assertStatus(200);
        $projectsPage->assertSee('Skyline Luxury Towers');

        // 9. Verify Public Preview
        $previewPage = $this->get(route('builder.projects.preview', ['project_id' => $projectId]));
        $previewPage->assertStatus(200);
        $previewPage->assertSee('Skyline Luxury Towers');
        $previewPage->assertSee('Prestige Developers');
        $previewPage->assertSee('Whitefield, Bangalore');
    }
}
