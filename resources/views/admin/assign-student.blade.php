@extends('layouts.app')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Okay'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        </script>
    @endif

    <div class="row">
        <div class="col-md-6 col-xl-3">
             <div class="card">
                  <div class="card-body">
                       <div class="row">
                            <div class="col-6">
                                 <div class="avatar-md bg-primary rounded">
                                      <i class="bx bx-layer avatar-title fs-24 text-white"></i>
                                 </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                 <p class="text-muted mb-0 text-truncate">Class Name</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $class->name }}</h3>
                            </div> <!-- end col -->
                       </div> <!-- end row-->
                  </div> <!-- end card body -->
             </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-md-6 col-xl-3">
             <div class="card">
                  <div class="card-body">
                       <div class="row">
                            <div class="col-6">
                                 <div class="avatar-md bg-success rounded">
                                      <i class="bx bx-award avatar-title fs-24 text-white"></i>
                                 </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                 <p class="text-muted mb-0 text-truncate">Teacher</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $class->teacher }}</h3>
                            </div> <!-- end col -->
                       </div> <!-- end row-->
                  </div> <!-- end card body -->
             </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-md-6 col-xl-3">
             <div class="card">
                  <div class="card-body">
                       <div class="row">
                            <div class="col-6">
                                 <div class="avatar-md bg-danger rounded">
                                      <i class="bx bxs-backpack avatar-title fs-24 text-white"></i>
                                 </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                 <p class="text-muted mb-0 text-truncate">Grade</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $class->grade->name }}</h3>
                            </div> <!-- end col -->
                       </div> <!-- end row-->
                  </div> <!-- end card body -->
             </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-md-6 col-xl-3">
             <div class="card">
                  <div class="card-body">
                       <div class="row">
                            <div class="col-6">
                                 <div class="avatar-md text-bg-warning rounded">
                                      <i class="bx bx-dollar-circle avatar-title fs-24"></i>
                                 </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                 <p class="text-muted mb-0 text-truncate">Subject</p>
                                 <h3 class="text-dark mt-1 mb-0">{{ $class->subject->name }}</h3>
                            </div> <!-- end col -->
                       </div> <!-- end row-->
                  </div> <!-- end card body -->
             </div> <!-- end card -->
        </div> <!-- end col -->
   </div> <!-- end row -->

    <div class="hstack gap-2">
        <button class="btn btn-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
            aria-expanded="false" aria-controls="collapseExample">
            Assign Student
        </button>
    </div>
    <div class="collapse" id="collapseExample">
        <div class="card mb-0">
            <div class="card-body">
                <form method="POST" action="/assign-student/store">
                    @csrf
                    <div class="mb-3">
                        <select class="form-control" data-choices name="student_id" id="choices-single-default">
                            <option value="">You can search by student name / id</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" data-name="{{ strtolower($student->name) }}">
                                    {{ $student->id }} - {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" id="simpleinput" class="form-control" name="class_id" hidden
                            value="{{ $class->id }}">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Added Year</label>
                        <input type="text" id="simpleinput" class="form-control" name="added_year">
                    </div>

                    <div class="mb-3">
                        <input type="text" id="simpleinput" class="form-control" name="new_old_status" hidden
                            value="{{ $class->new_old_status }}">
                    </div>

                    <button type="submit" class="btn btn-success">Assign</button>
                </form>
            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-1 anchor" id="basic">
                    Assign Student Details
                </h5>
                <button class="btn btn-success"
                    onclick="event.preventDefault(); document.getElementById('upgrade-form').submit();">
                    Upgrade<i class="bi bi-arrow-up"></i>
                </button>
                <form id="upgrade-form" method="POST" action="{{ route('assign-student.upgrade', $class->id) }}" style="display: none;">
                    @csrf
                </form>

            </div>
            <div class="table-responsive">
                <table class="table table-centered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Student Name</th>
                            <th scope="col">Class Name</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Year</th>
                            <th scope="col">Added Date & Time</th>
                            <th scope="col">New / Old</th>
                            <th scope="col">Assign Status</th>
                            <th scope="col">Deactive Date</th>
                            <th scope="col">Deactive Reason</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assigns as $assign)
                            <tr class="{{ $assign->status == 'inactive' ? 'opacity-50' : '' }}">
                                <td>{{ $assign->student->name }}</td>
                                <td>{{ $assign->classModel->name }}</td>
                                <td>{{ $assign->classModel->grade->name }}</td>
                                <td>{{ $assign->added_year }}</td>
                                <td>{{ $assign->added_datetime }}</td>
                                <td>{{ $assign->new_old_status }}</td>
                                <td class="{{ $assign->status == 'active' ? 'table-success' : 'table-danger' }}">
                                    {{ $assign->status }}
                                </td>
                                <td>{{ $assign->deactivate_date }}</td>
                                <td>{{ $assign->deactivate_reason }}</td>
                                <td>
                                    <button class="btn btn-outline-info" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-id="{{ $assign->id }}"
                                        data-added-year="{{ $assign->added_year }}" data-status="{{ $assign->status }}"
                                        data-deactivate-date="{{ $assign->deactivate_date }}"
                                        data-deactivate-reason="{{ $assign->deactivate_reason }}"
                                        data-new-old-status="{{ $assign->new_old_status }}" onclick="populateForm(this)">
                                        Edit
                                    </button>

                                    {{-- Update Form --}}
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form method="POST" id="updateForm"
                                                        action="/assign-student/update/{{ $assign->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="added_year" class="form-label">Added Year</label>
                                                            <select id="added_year" class="form-control" name="added_year">
                                                                <!-- Options will be dynamically added here -->
                                                            </select>
                                                        </div>


                                                        <div class="mb-3">
                                                            <label for="classname" class="form-label">New / Old</label><br>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="new_old_status" id="newcheck" value="new">
                                                                <label class="form-check-label" for="new">New</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="new_old_status" id="oldcheck" value="old">
                                                                <label class="form-check-label" for="old">Old</label>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalDelete">
                                        Delete</button>
                                </td>

                                {{-- Delete Form --}}
                                <div class="modal fade" id="exampleModalDelete" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <form method="POST" id="deleteForm"
                                                    action="/assign/deactivate/{{ $assign->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3" id="deactivateFields1">
                                                        <label for="simpleinput" class="form-label">Deactivate
                                                            Reason</label>
                                                        <input type="text" id="deactivate_reason" class="form-control"
                                                            name="deactivate_reason" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function populateForm(button) {
            // Get the data from the button's data attributes
            const Id = button.getAttribute('data-id');
            const addedYear = button.getAttribute('data-added-year');
            const status = button.getAttribute('data-status');
            const deactivateDate = button.getAttribute('data-deactivate-date');
            const deactivateReason = button.getAttribute('data-deactivate-reason');
            const newOldStatus = button.getAttribute('data-new-old-status');

            // Fill in the form fields
            document.getElementById('added_year').value = addedYear;

            if (status === 'active') {
                document.getElementById('status_active').checked = true;
                document.getElementById('status_inactive').checked = false;
            } else {
                document.getElementById('status_active').checked = false;
                document.getElementById('status_inactive').checked = true;
            }

            if (status === 'inactive') {
                document.getElementById('deactivateFields').style.display = 'block';
                document.getElementById('deactivate_date').value = deactivateDate;
                document.getElementById('deactivate_reason').value = deactivateReason;
            } else {
                document.getElementById('deactivateFields').style.display = 'none';
            }

            if (newOldStatus === 'new') {
                document.getElementById('newcheck').checked = true;
            } else if (newOldStatus === 'old') {
                document.getElementById('oldcheck').checked = true;
            }
        }

        function toggleFields() {
            const status = document.querySelector('input[name="status"]:checked').value;
            const deactivateFields = document.getElementById('deactivateFields');

            if (status === 'inactive') {
                deactivateFields.style.display = 'block';
            } else {
                deactivateFields.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addedYearSelect = document.getElementById('added_year');
            const currentYear = new Date().getFullYear();
            const startYear = 2000;

            // Populate the select options
            for (let year = startYear; year <= currentYear; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                addedYearSelect.appendChild(option);
            }
        });
    </script>
@endsection
