<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObjectionController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/results/export/{election_id}', [DashboardController::class, 'exportResults'])->name('results.export');
Route::get('/results/export-pdf/{election_id}', [DashboardController::class, 'exportResultsPdf'])->name('results.export_pdf');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/language/{lang}', [AuthController::class, 'setLanguage'])->name('language.set');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/preview/voter', [DashboardController::class, 'previewVoter'])->name('preview.voter');
    Route::get('/preview/candidate', [DashboardController::class, 'previewCandidate'])->name('preview.candidate');
    Route::get('/preview/exit', [DashboardController::class, 'exitPreview'])->name('preview.exit');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/change-password', [DashboardController::class, 'changePassword'])->name('profile.change_password');
    Route::post('/profile/update-accessibility', [DashboardController::class, 'updateAccessibility'])->name('profile.update_accessibility');
    Route::post('/accessibility/toggle-contrast', [DashboardController::class, 'toggleContrast'])->name('accessibility.toggle_contrast');

    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates');
    Route::get('/candidates/apply', [CandidateController::class, 'apply'])->name('candidates.apply');
    Route::post('/candidates/apply', [CandidateController::class, 'storeApplication'])->name('candidates.apply.store');

    Route::get('/results', [DashboardController::class, 'results'])->name('results');

    Route::get('/vote/{election_id}', [VoteController::class, 'showVoteForm'])->name('vote.form');
    Route::post('/vote/{election_id}', [VoteController::class, 'castVote'])->name('vote.cast');

    Route::get('/vote/assisted/{election_id}', [VoteController::class, 'showAssistedVoteForm'])->name('vote.assisted');
    Route::post('/vote/assisted/lookup/{election_id}', [VoteController::class, 'lookupVoter'])->name('vote.assisted.lookup');
    Route::post('/vote/assisted/{election_id}', [VoteController::class, 'castAssistedVote'])->name('vote.assisted.cast');

    Route::get('/objections/submit', [ObjectionController::class, 'submitForm'])->name('objections.submit');
    Route::post('/objections/submit', [ObjectionController::class, 'submit'])->name('objections.submit.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates');
    Route::get('/elections', [AdminController::class, 'elections'])->name('elections');
    Route::get('/votes', [AdminController::class, 'votesManage'])->name('votes');
    Route::get('/results', [AdminController::class, 'resultsManage'])->name('results');
        Route::get('/results/export/{election_id}', [AdminController::class, 'exportResults'])->name('results.export');
        Route::get('/results/export-pdf/{election_id}', [AdminController::class, 'exportResultsPdf'])->name('results.export_pdf');

    Route::get('/audit_logs', [AdminController::class, 'auditLogs'])->name('audit_logs');
    Route::get('/suspicious_logs', [AdminController::class, 'suspiciousLogs'])->name('suspicious_logs');
    Route::get('/assisted-votes', [AdminController::class, 'assistedVotes'])->name('assisted_votes');
    Route::get('/accessibility-logs', [AdminController::class, 'accessibilityLogs'])->name('accessibility_logs');

    Route::get('/objections', [AdminController::class, 'objections'])->name('objections');
    Route::get('/objections/{id}', [AdminController::class, 'viewObjection'])->name('objections.view');
    Route::post('/objections/{id}/resolve', [AdminController::class, 'resolveObjection'])->name('objections.resolve');

    Route::get('/violations', [AdminController::class, 'violations'])->name('violations');
    Route::get('/violations/{id}', [AdminController::class, 'viewViolation'])->name('violations.view');
    Route::post('/violations/{id}/resolve', [AdminController::class, 'resolveViolation'])->name('violations.resolve');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
    Route::match(['get', 'post'], '/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::match(['get', 'post'], '/announcements/edit/{id}', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::get('/announcements/publish/{id}', [AnnouncementController::class, 'publish'])->name('announcements.publish');
    Route::get('/announcements/unpublish/{id}', [AnnouncementController::class, 'unpublish'])->name('announcements.unpublish');
    Route::get('/announcements/delete/{id}', [AnnouncementController::class, 'delete'])->name('announcements.delete');

    Route::middleware(['superadmin'])->group(function () {
        Route::get('/candidates/delete-all', [AdminController::class, 'deleteAllCandidates'])->name('candidates.delete_all');
        Route::match(['get', 'post'], '/candidates/register/{position}', [AdminController::class, 'registerCandidate'])->name('candidates.register');
        Route::post('/candidates/approve/{candidate_id}', [AdminController::class, 'approveCandidate'])->name('candidates.approve');
        Route::get('/candidates/reject/{candidate_id}', [AdminController::class, 'rejectCandidate'])->name('candidates.reject');
        Route::get('/candidates/nomination-support/{candidate_id}', [AdminController::class, 'nominationSupport'])->name('candidates.nomination_support');
        Route::post('/candidates/nomination-support/{candidate_id}', [AdminController::class, 'addNominationSupport'])->name('candidates.nomination_support.add');

        Route::match(['get', 'post'], '/elections/create', [AdminController::class, 'createElection'])->name('elections.create');
        Route::match(['get', 'post'], '/elections/edit/{election_id}', [AdminController::class, 'editElection'])->name('elections.edit');
        Route::get('/elections/transition/{election_id}/{status}', [AdminController::class, 'transitionStatus'])->name('elections.transition');
        Route::get('/elections/generate_results/{election_id}', [AdminController::class, 'generateResults'])->name('elections.generate_results');
        Route::get('/elections/declare-winner/{election_id}', [AdminController::class, 'declareWinner'])->name('elections.declare_winner');
        Route::get('/elections/revoke-winner/{election_id}', [AdminController::class, 'revokeWinner'])->name('elections.revoke_winner');
        Route::get('/elections/delete/{election_id}', [AdminController::class, 'deleteElection'])->name('elections.delete');

        // Routes removed: votes are immutable and cannot be deleted

        Route::get('/audit_logs/delete-all', [AdminController::class, 'deleteAllAuditLogs'])->name('audit_logs.delete_all');
        Route::get('/suspicious_logs/delete-all', [AdminController::class, 'deleteAllSuspiciousLogs'])->name('suspicious_logs.delete_all');

        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::match(['get', 'post'], '/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::match(['get', 'post'], '/users/edit/{user_id}', [AdminController::class, 'editUser'])->name('users.edit');
        Route::get('/users/delete/{user_id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/users/approve/{user_id}', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::get('/users/reject/{user_id}', [AdminController::class, 'rejectUser'])->name('users.reject');
        Route::match(['get', 'post'], '/settings', [AdminController::class, 'settings'])->name('settings');

        Route::get('/positions', [AdminController::class, 'positions'])->name('positions');
        Route::match(['get', 'post'], '/positions/create', [AdminController::class, 'createPosition'])->name('positions.create');
        Route::match(['get', 'post'], '/positions/edit/{id}', [AdminController::class, 'editPosition'])->name('positions.edit');
        Route::get('/positions/delete/{id}', [AdminController::class, 'deletePosition'])->name('positions.delete');
    });
});
