<div id="role-form-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="role-form-title">
                    Create New Role
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onclick="hideFlowBytesModal('role-form-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" id="role-form-data">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="roleName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="roleName" id="roleName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type role name" required="">
                    </div>
                    <div class="flex justify-end items-center col-span-2">
                        <input id="allCheckbox" type="checkbox" value="" name="allCheckbox"
                            onchange="toogleCheckAll(this, 'moduleActions[]')"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="allCheckbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Check
                            All</label>
                    </div>
                    <div class="border border-gray-100 shadow-md dark:border-gray-700 rounded-xl p-4 col-span-2">
                        <div class="max-h-60 overflow-y-auto">
                            @foreach ($groupedModuleActions as $groupedModuleAction)
                                <div class="mb-4 flex flex-col gap-1 px-1">
                                    <label for="roleName"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $groupedModuleAction['module_name'] }}</label>
                                    <div class="grid gap-4 grid-cols-4">
                                        @foreach ($groupedModuleAction['actions'] as $action)
                                            <div class="grid grid-cols-4">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" name="moduleActions[]"
                                                        value="{{ $action['module_action_id'] }}"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                        id="moduleAction{{ $action['module_action_id'] }}">
                                                    <label class="text-sm font-medium text-gray-900 dark:text-gray-300"
                                                        for="moduleAction{{ $action['module_action_id'] }}">{{ $action['name'] }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                </div>
                <button type="button" id="role-form-submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Save data
                </button>
            </form>
        </div>
    </div>
</div>
