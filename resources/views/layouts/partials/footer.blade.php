
<!-- ================================================================ -->
<!-- FILE: resources/views/layouts/partials/footer.blade.php -->
<!-- ================================================================ -->
<footer class="bg-light border-top py-3 mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small class="text-muted">
                    &copy; {{ date('Y') }} ArBif Management system - All right reserved
                </small>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    User: <strong>{{ Auth::user()->username }}</strong> | 
                    Role: <strong>{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong> |
                    A. Status: <span class="badge bg-success">{{ ucfirst(Auth::user()->status) }}</span>
                </small>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col text-center">
                <small class="text-muted">
                    Version: 1.0.0 | 
                    <a href="" class="text-decoration-none">Support</a> | 
                    <a href="" class="text-decoration-none">Privacy</a> | 
                    <a href="" class="text-decoration-none">Our Policies</a>
                </small>
            </div>
        </div>
    </div>
</footer>