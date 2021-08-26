<div class="white-box">
    <nav>
        <ul class="showClientTabs">
            <li class="clientProfile"><a href="{{ route('admin.clients.show', $client->id) }}"><i class="icon-user"></i> <span>@lang('modules.employees.profile')</span></a>
            </li>
            <li class="clientProjects"><a href="{{ route('admin.clients.projects', $client->id) }}"><i class="icon-layers"></i> <span>@lang('app.menu.tasks')</span></a>
            </li>
            <li class="clientInvoices"><a href="{{ route('admin.clients.invoices', $client->id) }}"><i class="icon-doc"></i> <span>@lang('app.menu.invoices')</span></a>
            </li>
        </ul>
    </nav>
</div>