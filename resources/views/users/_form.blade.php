
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 <div class="form-group">
     <label for="name">الأسم</label>
     <input  class="form-control" value="{{ old('name',$user->name) }}" type="text" name="name" id="name" required="">
 </div>
<div class="form-group">
     <label for="email">البريد الالكترونى</label>
     <input  class="form-control" value="{{ $user->email }}" type="text" name="email" id="email">
 </div>
 <div class="form-group">
      <label for="password">كلمة المرور</label>
      <input required="required"  class="form-control" type="password" name="password">
 </div>
 <div class="form-group">
      <label for="password_confirmation">تأكيد كلمة المرور</label>
      <input required="required"  class="form-control" type="password" name="password_confirmation" id="password_confirmation">
 </div>
 <div class="form-group">
      <label>الصلاحيات ك</label>
      <select name="role" class="form-control">
          @foreach(\App\Role::get() as $role)
              <option @if($user->role==$role->id) selected="" @endif value="{{$role->id}}">{{$role->display_name}}</option>
          @endforeach
      </select>
 </div>

<button type="submit" class="btn btn-primary">{{ trans('app.Submit') }}</button>