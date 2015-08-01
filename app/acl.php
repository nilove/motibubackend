<?php

use Motibu\Models\Agency;
use Motibu\Models\Client;
use Motibu\Models\Role;
use Motibu\Models\User;

ACL::withRole('Candidate')
	->grant( [
		'job.read' => true,
		'job.apply' => function () {
			return true;
		},
		'candidate.read' => function ($candidateId) {
			if (\Auth::user()->userProfile->id == $candidateId)
				return true;
		}
	]);

ACL::withRole('Agency Admin')
	->grant( [
		'agency.create' => function () {
			return true;
		},
		'agency.read' => function ($agencyId) {
			if (Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'agency.update' => function ($agencyId) {
			if (Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'agency.delete' => function ($agencyId) {
			if (Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'agency.clients' => function ($agencyId) {
			if (Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'agency.agents' => function ($agencyId) {
			// return false;
			if (Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'agent.update' => function ($agent_id) {
			$agent = Agent::find($agent_id);
			if ($agent && Auth::user()->hasMtmAgency(Agency::find($agent->agency_id)))
				return true;
		},
		'client.create' => function () {
			$agencyId = Input::get('agency_id', false);

			if ($agencyId && Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'client.show' => function ($clientId) {
			$client = Client::find($clientId);
			if (Auth::user()->hasMtmAgency(Agency::find($client->agency_id)))
				return true;
		},
		'agent.create' => function () {
			$agencyId = Input::get('agency_id', false);

			if ($agencyId && Auth::user()->hasMtmAgency(Agency::find($agencyId)))
				return true;
		},
		'staff.create' => function () {
			$clientId = \Input::get('client_id');
			$client = Client::find($clientId);
			if (Auth::user()->hasMtmAgency(Agency::find($client->agency_id)))
				return true;
		},
		'staff.read' => function ($clientId) {
			$client = Client::find($clientId);
			if (Auth::user()->hasMtmAgency(Agency::find($client->agency_id)))
				return true;
		},
		'job.create' => function () {
			$clientId = \Input::get('client_id');
			$client = Client::find($clientId);
			if (Auth::user()->hasMtmAgency(Agency::find($client->agency_id)))
				return true;
		}
	]);

ACL::withRole('SaaS Client Admin')
	->grant( [
		'*.create' => true,
		'*.read' => true,
		'*.update' => true,
		'*.delete' => true,
	]);

ACL::withRole('Super Admin')
	->grant( [
		'*.create' => true,
		'*.read' => true,
		'*.update' => true,
		'*.delete' => true,
	]);
