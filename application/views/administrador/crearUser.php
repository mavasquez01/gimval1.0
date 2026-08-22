
    
    <main class="d-flex justify-content-center py-4 min-vh-100">

    <div class="container-fluid" style="max-width: 420px;">
        <div class="row text-center mb-4">
            <div class="col-12">
                <h2 class="text-white fw-bold">
                    Crear Usuario
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="">
                    <div class="mb-4">
                        <label for="rut"
                            class="form-label text-white">
                            RUT
                        </label>

                        <input type="text"
                            class="form-control custom-input"
                            name="rut"
                            placeholder="20.456.678-9">
                    </div>
                    <div class="mb-4">
                        <label for="rol"
                            class="form-label text-white">
                            Rol
                        </label>
                        <select class="form-select custom-input"
                            name="rol">

                            <option selected disabled>
                                Seleccionar rol
                            </option>

                            <option value="alumna">
                                Alumna
                            </option>

                            <option value="profesor">
                                Profesor
                            </option>

                            <option value="admin">
                                Administrador
                            </option>

                        </select>
                    </div>
                    <div class="d-grid mt-5">
                        <button type="submit"
                            class="btn btn-primary py-3 fw-bold rounded-4">
                            GUARDAR
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</main>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>