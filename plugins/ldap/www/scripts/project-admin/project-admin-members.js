/*
 * Copyright Enalean (c) 2017. All rights reserved.
 *
 * Tuleap and Enalean names and logos are registrated trademarks owned by
 * Enalean SAS. All other trademarks or names are properties of their respective
 * owners.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

import { get, modal as createModal } from "tlp";
import { initLdapBindingPreview } from "./preview-for-select2.js";

document.addEventListener("DOMContentLoaded", () => {
    initLdapGroupsAutocompleter();
    initLdapLinkModal();
});

function initLdapLinkModal() {
    const button = document.getElementById("project-admin-members-link-ldap-button");
    if (!button) {
        return;
    }
    const modal = createModal(document.getElementById(button.dataset.targetModalId));

    button.addEventListener("click", () => {
        modal.show();
    });
}

function initLdapGroupsAutocompleter() {
    const select = document.getElementById("project-admin-members-ldap-group-select");
    if (!select) {
        return;
    }
    const preserve = document.getElementById("project-admin-members-ldap-group-preserve");
    const button = document.getElementById("project-admin-members-ldap-group-link");
    const preview = document.getElementById("project-admin-members-ldap-group-link-preview");
    const synchronize = document.getElementById("project-admin-members-ldap-group-sync");
    const project_id = select.dataset.projectId;
    const display_name = select.dataset.displayName;
    const base_url = "/plugins/ldap/bind_members_confirm.php";

    initLdapBindingPreview(
        {
            preserve,
            button,
            preview,
            display_name,
            select
        },
        getUsersToConfirm
    );

    async function getUsersToConfirm(chosen_ldap_group) {
        const params = {
            ldap_group: chosen_ldap_group,
            group_id: project_id,
            preserve_members: preserve.checked ? 1 : 0,
            synchronize: synchronize.checked ? 1 : 0
        };

        const response = await get(base_url, { params });

        return await response.json();
    }
}
