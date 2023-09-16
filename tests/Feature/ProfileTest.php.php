<?php


use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateProfile()
    {

        $user = User::factory()->create();
        $userData = [
            'name' => 'John Doe',
            'track' => 'IT',
            'bio' => 'Lorem ipsum dolor sit amet.',
        ];


        $response = $this->actingAs($user)
            ->put('/api/individuals/profile/'.$user->id, $userData); // افتراضيًا يتم استدعاء الوظيفة updateProfile()


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $userData['name'],
                    'email' => $user->email,
                    'imageUrl' => $user->imageUrl,
                    'track' => $userData['track'],
                    'bio' => $userData['bio'],
                ],
            ],
        ]);


        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $userData['name'],
            'track' => $userData['track'],
            'bio' => $userData['bio'],
        ]);
    }

    public function testUpdatePersonalInfo()
    {

        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
        $profileData = [
            'governate' => 'Cairo',
            'university' => 'Cairo University',
            'faculty' => 'Computer Science',
            'birthDate' => '1990-01-01',
            'emailProfile' => 'example@example.com',
            'phoneNumber' => '123456789',
            'projects' => 'Project 1, Project 2',
            'progLanguages' => 'PHP, JavaScript',
            'cvUrl' => 'http://example.com/cv',
            'githubUrl' => 'http://github.com/username',
            'linkedinUrl' => 'http://linkedin.com/username',
            'behanceUrl' => 'http://behance.com/username',
            'twitterUrl' => 'http://twitter.com/username',
            'facebookUrl' => 'http://facebook.com/username',
        ];


        $response = $this->actingAs($user)
            ->put('/api/individuals/profile/personal-info/'.$user->id, $profileData); // افتراضيًا يتم استدعاء الوظيفة updatePersonalInfo()


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => 'Pesonal information updated successfully',
            'data' => [
                'user' => [
                    'id' => $userProfile->id,
                    'governate' => $profileData['governate'],
                    'university' => $profileData['university'],
                    'faculty' => $profileData['faculty'],
                    'birthDate' => $profileData['birthDate'],
                    'emailProfile' => $profileData['emailProfile'],
                    'phoneNumber' => $profileData['phoneNumber'],
                    'projects' => $profileData['projects'],
                    'progLanguages' => $profileData['progLanguages'],
                    'cvUrl' => $profileData['cvUrl'],
                    'githubUrl' => $profileData['githubUrl'],
                    'linkedinUrl' => $profileData['linkedinUrl'],
                    'behanceUrl' => $profileData['behanceUrl'],
                    'twitterUrl' => $profileData['twitterUrl'],
                    'facebookUrl' => $profileData['facebookUrl'],
                ],
            ],
        ]);

        
        $this->assertDatabaseHas('user_profiles', [
            'id' => $userProfile->id,
            'governate' => $profileData['governate'],
            'university' => $profileData['university'],
            'faculty' => $profileData['faculty'],
            'birth_date' => $profileData['birthDate'],
            'email_profile' => $profileData['emailProfile'],
            'phone_number' => $profileData['phoneNumber'],
            'projects' => $profileData['projects'],
            'prog_languages' => $profileData['progLanguages'],
            'cv_url' => $profileData['cvUrl'],
            'github_url' => $profileData['githubUrl'],
            'linkedin_url' => $profileData['linkedinUrl'],
            'behance_url' => $profileData['behanceUrl'],
            'twitter_url' => $profileData['twitterUrl'],
            'facebook_url' => $profileData['facebookUrl'],
        ]);
    }
}
