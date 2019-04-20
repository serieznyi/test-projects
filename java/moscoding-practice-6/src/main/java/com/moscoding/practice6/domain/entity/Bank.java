package com.moscoding.practice6.domain.entity;

import com.moscoding.practice6.validation.UniqueBankNameConstraint;
import lombok.Getter;
import lombok.Setter;

import javax.persistence.*;
import javax.validation.constraints.NotBlank;
import javax.validation.constraints.Size;
import java.util.ArrayList;
import java.util.List;

@Getter
@Setter
@Entity
@Table(name = "bank")
@UniqueBankNameConstraint
final public class Bank {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank
    @Size(min=5, max=50)
    @Column(unique = true)
    private String name;

    @OneToMany(mappedBy = "bank")
    private List<AccountType> accountTypes = new ArrayList<>(2);
}
