@forelse($employeeDocs as $key=>$employeeDoc)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ ucwords($employeeDoc->name) }}</td>
                            <td>
                                <a href="{{ route('member.employee-docs.download', $employeeDoc->id) }}"
                                data-toggle="tooltip" data-original-title="Download"
                                class="btn btn-inverse btn-circle"><i
                                            class="fa fa-download"></i></a>
                                <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
                                data-toggle="tooltip" data-original-title="View"
                                class="btn btn-info btn-circle"><i
                                            class="fa fa-search"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">@lang('messages.noDocsFound')</td>
                        </tr>
                    @endforelse