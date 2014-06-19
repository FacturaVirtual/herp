<?php

class ContactController extends \BaseController{

    public function index()
    {

        return View::Make('contact.index');

    }

    public function create(){

        return View::Make('contact.create');
    }

    public function store(){

        if (Contact::ValidatorFails())
        {
            return Redirect::to( URL::previous() )
                ->withErrors(Contact::getValidator()) 
                ->withInput();
        }
        else
        {

            if (Contact::StoreFails() )
            {
                return Redirect::to( URL::previous() )
                    ->withErrors($contact->errors()->all(':message'))
                    ->withInput();            
            }

            return Redirect::route('contact.index');
        }

    }

    public function edit($id)
    {

        $contact    = Contact::findOrFail($id);

        return View::Make('contact.edit')->with('contact', $contact);

    }

    public function update($id)
    {

        if (Contact::ValidatorFails())
        {
            return Redirect::to( URL::previous() )
                ->withErrors(Contact::getValidator()) 
                ->withInput();
        }
        else
        {

            $contact                    = Contact::find($id);

            $contact->name              = Input::get('name');
            $contact->fiscal_number     = Input::get('fiscal_number');

            if ( !$contact->save() )
            {
                return Redirect::to( URL::previous() )
                    ->withErrors($contact->errors()->all(':message'))
                    ->withInput();            
            }


            return Redirect::route('contact.index');
        }

    }

    public function destroy($id)
    {
        return "borrar contacto con id " . $id;

    }

    public function getDatatable()
    {

            return Datatable::collection(Contact::all( array('id', 'name', 'fiscal_number')))
                ->addColumn('checkbox', function($model)
                {
                    // return  '<input type="checkbox" class="selectAll"/>';
                    return Form::checkbox('id', $model->id);
                })
                ->addColumn('name',function($model)
                {
                    return link_to_route('contact.edit', $model->name, $model->id );
                })
                ->addColumn('fiscal_number',function($model)
                {
                    return $model->fiscal_number;
                })
                ->searchColumns('name', 'fiscal_number')
                ->orderColumns('name', 'fiscal_number')
                ->make();

    }
}