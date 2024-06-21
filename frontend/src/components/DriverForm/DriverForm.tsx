"use client";

import React from "react";

interface DriverFormProps {
  driver: string;
  setDriver: React.Dispatch<React.SetStateAction<string>>;
}

function DriverForm({ driver, setDriver }: DriverFormProps) {}

export default DriverForm;
