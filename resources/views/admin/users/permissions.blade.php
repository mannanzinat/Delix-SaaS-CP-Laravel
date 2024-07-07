<thead>
    <tr>
      <th scope="col">{{__('module')}}/{{__('sub-module')}}</th>
      <th scope="col">{{__('permissions')}}</th>
    </tr>
  </thead>
  <tbody>
      @foreach($permissions as $permission)
    <tr>
        <td><span class="text-capitalize">{{__($permission->attribute)}}</span></td>
      <td>
        @foreach($permission->keywords as $key=>$keyword)
          <div class="custom-control custom-checkbox">
              @if($keyword != "")
            <label class="custom-control-label" for="{{$keyword}}">
                <input type="checkbox" class="custom-control-input read common-key" name="permissions[]" value="{{$keyword}}" id="{{$keyword}}" {{in_array($keyword, $role_permissions) ? 'checked':''}}>
                <span>{{__($key)}}</span>
            </label>
            @endif
        </div>
        @endforeach
      </td>
    </tr>
    @endforeach

  </tbody>
