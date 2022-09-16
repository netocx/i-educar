<?php

namespace App\Models\Builders;

use App\Models\DataSearch\StudentFilter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegacyStudentBuilder extends LegacyBuilder
{
    public function whereStudent($student)
    {
        return $this->where('cod_aluno', $student);
    }

    public function whereStateNetwork($stateNetwork)
    {
        return $this->whereRaw('aluno_estado_id like ?', $stateNetwork);
    }

    public function whereBirthdate($birthdate)
    {
        return  $this->whereHas(
            'individual',
            function ($query) use ($birthdate) {
                $query->when($birthdate, function ($q) use ($birthdate) {
                    [$day, $month, $year] = explode('/', $birthdate);
                    $birthdate = sprintf('%d-%d-%d', $year, $month, $day);

                    $q->where('data_nasc', $birthdate);
                });
            }
        );
    }

    public function whereCpf($cpf)
    {
        return  $this->whereHas(
            'individual',
            function ($query) use ($cpf) {
                $query->when($cpf, fn ($q) => $q->where('cpf', $cpf));
            }
        );
    }

    public function whereRg($rg)
    {
        return  $this->whereHas(
            'individual.document',
            function ($query) use ($rg) {
                $query->when($rg, fn ($q) => $q->where('rg', $rg));
            }
        );
    }

    public function whereMotherName($name)
    {
        return $this->whereHas(
            'individual.father',
            fn ($q) => $q->whereRaw('slug ~* unaccent(?)', $name)
        );
    }

    public function whereStudentName($name)
    {
        return $this->whereHas(
            'person',
            fn ($q) => $q->whereRaw('slug ~* unaccent(?)', $name)
        );
    }

    public function whereFatherName($name)
    {
        return $this->whereHas(
            'individual.father',
            fn ($q) => $q->whereRaw('slug ~* unaccent(?)', $name)
        );
    }

    public function whereGuardianName($name)
    {
        return $this->whereHas(
            'individual.responsible',
            fn ($q) => $q->whereRaw('slug ~* unaccent(?)', $name)
        );
    }

    public function whereActive()
    {
        return $this->where('ativo', 1);
    }

    public function whereInep($inep)
    {
        return $this->whereHas('inep', fn ($q) => $q->where('cod_aluno_inep', $inep));
    }

    public function whereRegistrationYear($year)
    {
        return $this->whereHas(
            'registrations',
            fn ($q) => $q->where('ano', $year)
        );
    }

    public function whereSchool($school)
    {
        return $this->whereHas(
            'registrations',
            fn ($q) => $q->where('ref_ref_cod_escola', $school)
        );
    }

    public function whereCourse($course)
    {
        return $this->whereHas(
            'registrations',
            fn ($q) => $q->where('ref_cod_curso', $course)
        );
    }

    public function whereGrade($grade)
    {
        return $this->whereHas(
            'registrations.enrollments.schoolClass',
            fn ($q) => $q->where('ref_ref_cod_serie', $grade)
        );
    }

    public function whereRegistration($year, $course, $grade, $school)
    {
        return $this->whereHas(
            'registrations',
            function ($query) use ($year, $course, $grade, $school) {
                $query->when($year, fn ($q) => $q->where('ano', $year));
                $query->when($course, fn ($q) => $q->where('ref_cod_curso', $course));
                $query->when($school, fn ($q) => $q->where('ref_ref_cod_escola', $school));
                $query->when($grade, function ($q) use ($grade) {
                    $q->whereHas('enrollments.schoolClass', fn ($qs) => $qs->where('ref_ref_cod_serie', $grade));
                });
            }
        );
    }

    public function findStudentWithMultipleSearch(StudentFilter $studentFilter)
    {
        return $this->with(
            [
                'individual' => function (BelongsTo $query) {
                    $query->select(['idpes', 'idpes_mae', 'idpes_pai', 'nome_social']);
                    $query
                        ->with('father:nome,idpes', 'father.individual:cpf,idpes')
                        ->with('mother:nome,idpes', 'mother.individual:cpf,idpes')
                        ->with('responsible:nome,idpes', 'responsible.individual:cpf,idpes');
                },
                'person:idpes,nome',
                'inep:cod_aluno,cod_aluno_inep'
            ]
        )
            ->filter(
                [
                    'student' => $studentFilter->studentCode,
                    'student_name' => $studentFilter->studentName,
                    'mother_name' => $studentFilter->motherName,
                    'father_name' => $studentFilter->fatherName,
                    'guardian_name' => $studentFilter->responsableName,
                    'inep' => $studentFilter->inep,
                    'cpf' => $studentFilter->cpf,
                    'rg' => $studentFilter->rg,
                    'state_network' => $studentFilter->stateNetwork,
                    'birthdate' => $studentFilter->birthdate,
                    'registration' => [
                        'grade' => $studentFilter->grade,
                        'course' => $studentFilter->course,
                        'school' => $studentFilter->school,
                        'year' => $studentFilter->year,
                    ]
                ]
            )
            ->active()
            ->orderBy('data_cadastro', 'desc')
            ->paginate(
                $studentFilter->perPage,
                ['ref_idpes', 'cod_aluno', 'tipo_responsavel'],
                'pagina_' . $studentFilter->pageName
            );
    }
}
