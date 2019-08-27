<?php

use App\Book;
use Illuminate\Http\Request;
use Routes\Web;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//全体のRouteの定義

Route::get('/', function () {
    return view('books');
});


Route::group(['middleware' => ['web']],function(){
    
    //一番上の階層を表示する
    //Bookテーブルのデータを全て抽出してbooks変数を入れてbooksに加工してviewに渡す
    Route::get('/',function(){
      $books = Book::all();
      return view('books',['books' => $books]);
    });
    
    //新しい本の情報を投稿する
    Route::post('/book',function(Request $request){
      $validator = Validator::make($request->all(),[
        'name' => 'required|max:255',
      ]);
      
      //データの確認  
      if($validator->fails()){
           return redirect('/')
             ->withInput()
             ->withErrors($validator);
      }
      
      $book = new Book; //ORMオブジェクトリレションマッピング
      $book->title = $request->name;
      $book->save();
      
      return redirect('/');
    });
    
    //本の番号を出して本を削除する
    Route::delete('/book/{book}',function(Book $book){
      $book->delete();
      
      return redirect('/');
    });
    
  });
