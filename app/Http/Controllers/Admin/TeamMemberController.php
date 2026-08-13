<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $members = TeamMember::orderBy('sort_order')->paginate(30);

        return view('admin.team.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.team.form', ['member' => new TeamMember]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->images->store($request->file('photo'), 'team');
        }
        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Member added.');
    }

    public function edit(TeamMember $member): View
    {
        return view('admin.team.form', compact('member'));
    }

    public function update(TeamMember $member, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->images->replace($request->file('photo'), 'team', $member->photo);
        }
        $member->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Member updated.');
    }

    public function destroy(TeamMember $member): RedirectResponse
    {
        $photo = $member->photo;
        $member->delete();
        $this->images->delete($photo);

        return back()->with('success', 'Removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'group' => ['required', 'in:trustee,team,veterinarian,volunteer'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
