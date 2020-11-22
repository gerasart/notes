<?php

    namespace App\Http\Controllers;

    use App\Notes;
    use Illuminate\Support\Facades\File;
    use Illuminate\Http\Request;

    class NotesController extends Controller {

        public function getNotes() {
            $notes = Notes::paginate( 2 );
            return response()->json( [ 'data' => $notes ] );
        }

        public function addNote( Request $request ) {
            $data = $request->all();
            $note = Notes::create( $data );
            return response()->json( [ 'data' => $data, 'status' => $note ] );
        }

        public function imageUploadNote( Request $request ) {
            $request->validate( [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ] );
            $imageName = $request->image->getClientOriginalName();
            $request->image->move( public_path( 'images/notes' ), $imageName );
            return response()->json( [ 'filename' => $request->image->getClientOriginalName() ] );
        }

        public function removeNote( Request $request ) {
            $data        = $request->all();
            $public_path = public_path( 'images/notes/' ) . $data['img'];
            Notes::find( $data['id'] )->delete();
            File::delete( $public_path );
            return response()->json( [ 'data' => $data ] );
        }
    }
