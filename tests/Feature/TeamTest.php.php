<?php

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {

        $team1 = Team::factory()->create(['created_at' => now()->subDays(2)]);
        $team2 = Team::factory()->create(['created_at' => now()->subDays(1)]);
        $team3 = Team::factory()->create(['created_at' => now()]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $team1->members()->attach($user1);
        $team1->members()->attach($user2);
        $team2->members()->attach($user2);
        $team3->members()->attach($user1);
        $team3->members()->attach($user3);


        $response = $this->get('/api/teams');


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => '',
            'data' => [
                'teams' => [
                    [
                        'id' => $team3->id,
                        'name' => $team3->name,

                    ],
                    [
                        'id' => $team2->id,
                        'name' => $team2->name,

                    ],
                    [
                        'id' => $team1->id,
                        'name' => $team1->name,

                    ],
                ],
            ],
        ]);
    }


    public function testStore()
    {

        $user = User::factory()->create();

        $teamData = [
            'name' => 'New Team',

        ];


        $response = $this->actingAs($user)
            ->post('/api/teams', $teamData); // افتراضيًا يتم استدعاء الوظيفة store()


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => 'Team created successfully.',
            'data' => [
                'team' => [
                    'id' => 1,
                    'name' => $teamData['name'],

                ],
            ],
        ]);


        $this->assertDatabaseHas('teams', [
            'name' => $teamData['name'],

        ]);


        $this->assertDatabaseHas('team_user', [
            'team_id' => 1,
            'user_id' => $user->id,
        ]);
    }


    public function testUpdate()
    {

        $user = User::factory()->create();
        $team = Team::factory()->create();

        $updatedTeamData = [
            'name' => 'Updated Team',

        ];


        $response = $this->actingAs($user)
            ->put('/api/teams/'.$team->id, $updatedTeamData); // افتراضيًا يتم استدعاء الوظيفة update()


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => 'Team updated successfully.',
            'data' => [
                'team' => [
                    'id' => $team->id,
                    'name' => $updatedTeamData['name'],

                ],
            ],
        ]);


        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $updatedTeamData['name'],

        ]);
    }




    public function testDelete()
    {

        $user = User::factory()->create();
        $team = Team::factory()->create();


        $response = $this->actingAs($user)
            ->delete('/api/teams/'.$team->id); // افتراضيًا يتم استدعاء الوظيفة delete()


        $response->assertStatus(200);
        $response->assertJson([
            'result' => true,
            'message' => 'Team deleted successfully.',
            'data' => null,
        ]);

        
        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);
    }
}
