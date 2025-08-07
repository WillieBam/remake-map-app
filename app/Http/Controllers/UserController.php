<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        // Check if name/email exists in database

        // Mass assignment (name and email)

        // Find and set country Id
        // Find and set role Id

        // Hash password

        // Save user to database

        // return OK response
    }

    public function deleteUser($id)
    {
        // Check access level of session user (middleware)

        // Check if id exists in database

        // Delete user from database

        // Return OK response
    }

    public function updateUser(Request $request)
    {
        // Check if id matches with session user (checked in middleware)

        // Check if id exists in database

        // Mass assignment (name and email)
        // Update country Id
        // Update password (remember to hash it)

        // Save user to database

        // Return OK response
    }

    public function getUser($id)
    {
        // Check access level of session user (middleware)

        // Check if id exists in database

        // Get user from database

        // Return user data
    }

    public function getUsers()
    {
        // check access level of session user (middleware)

        // Get all users from database

        // return users
    }

    public function banUser($id)
    {
        // Check access level of session user (middleware)

        // Check if id exists in database

        // Ban user (set is_banned flag to true)

        // Return OK response
    }
}
