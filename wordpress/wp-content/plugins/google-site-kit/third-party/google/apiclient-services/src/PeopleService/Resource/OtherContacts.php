<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace Google\Site_Kit_Dependencies\Google\Service\PeopleService\Resource;

use Google\Site_Kit_Dependencies\Google\Service\PeopleService\CopyOtherContactToMyContactsGroupRequest;
use Google\Site_Kit_Dependencies\Google\Service\PeopleService\ListOtherContactsResponse;
use Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person;
use Google\Site_Kit_Dependencies\Google\Service\PeopleService\SearchResponse;
/**
 * The "otherContacts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $peopleService = new Google\Service\PeopleService(...);
 *   $otherContacts = $peopleService->otherContacts;
 *  </code>
 */
class OtherContacts extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Copies an "Other contact" to a new contact in the user's "myContacts" group
     * (otherContacts.copyOtherContactToMyContactsGroup)
     *
     * @param string $resourceName Required. The resource name of the "Other
     * contact" to copy.
     * @param CopyOtherContactToMyContactsGroupRequest $postBody
     * @param array $optParams Optional parameters.
     * @return Person
     */
    public function copyOtherContactToMyContactsGroup($resourceName, \Google\Site_Kit_Dependencies\Google\Service\PeopleService\CopyOtherContactToMyContactsGroupRequest $postBody, $optParams = [])
    {
        $params = ['resourceName' => $resourceName, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('copyOtherContactToMyContactsGroup', [$params], \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person::class);
    }
    /**
     * List all "Other contacts", that is contacts that are not in a contact group.
     * "Other contacts" are typically auto created contacts from interactions.
     * (otherContacts.listOtherContacts)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param int pageSize Optional. The number of "Other contacts" to include
     * in the response. Valid values are between 1 and 1000, inclusive. Defaults to
     * 100 if not set or set to 0.
     * @opt_param string pageToken Optional. A page token, received from a previous
     * `ListOtherContacts` call. Provide this to retrieve the subsequent page. When
     * paginating, all other parameters provided to `ListOtherContacts` must match
     * the call that provided the page token.
     * @opt_param string readMask Required. A field mask to restrict which fields on
     * each person are returned. Multiple fields can be specified by separating them
     * with commas. Valid values are: * emailAddresses * metadata * names *
     * phoneNumbers
     * @opt_param bool requestSyncToken Optional. Whether the response should
     * include `next_sync_token`, which can be used to get all changes since the
     * last request. For subsequent sync requests use the `sync_token` param
     * instead. Initial sync requests that specify `request_sync_token` have an
     * additional rate limit.
     * @opt_param string syncToken Optional. A sync token, received from a previous
     * `ListOtherContacts` call. Provide this to retrieve only the resources changed
     * since the last request. Sync requests that specify `sync_token` have an
     * additional rate limit. When the `syncToken` is specified, resources deleted
     * since the last sync will be returned as a person with [`PersonMetadata.delete
     * d`](/people/api/rest/v1/people#Person.PersonMetadata.FIELDS.deleted) set to
     * true. When the `syncToken` is specified, all other parameters provided to
     * `ListOtherContacts` must match the call that provided the sync token.
     * @return ListOtherContactsResponse
     */
    public function listOtherContacts($optParams = [])
    {
        $params = [];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], \Google\Site_Kit_Dependencies\Google\Service\PeopleService\ListOtherContactsResponse::class);
    }
    /**
     * Provides a list of contacts in the authenticated user's other contacts that
     * matches the search query. The query matches on a contact's `names`,
     * `emailAddresses`, and `phoneNumbers` fields that are from the OTHER_CONTACT
     * source. **IMPORTANT**: Before searching, clients should send a warmup request
     * with an empty query to update the cache. See
     * https://developers.google.com/people/v1/other-
     * contacts#search_the_users_other_contacts (otherContacts.search)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param int pageSize Optional. The number of results to return. Defaults
     * to 10 if field is not set, or set to 0. Values greater than 10 will be capped
     * to 10.
     * @opt_param string query Required. The plain-text query for the request. The
     * query is used to match prefix phrases of the fields on a person. For example,
     * a person with name "foo name" matches queries such as "f", "fo", "foo", "foo
     * n", "nam", etc., but not "oo n".
     * @opt_param string readMask Required. A field mask to restrict which fields on
     * each person are returned. Multiple fields can be specified by separating them
     * with commas. Valid values are: * emailAddresses * metadata * names *
     * phoneNumbers
     * @return SearchResponse
     */
    public function search($optParams = [])
    {
        $params = [];
        $params = \array_merge($params, $optParams);
        return $this->call('search', [$params], \Google\Site_Kit_Dependencies\Google\Service\PeopleService\SearchResponse::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\Resource\OtherContacts::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PeopleService_Resource_OtherContacts');
