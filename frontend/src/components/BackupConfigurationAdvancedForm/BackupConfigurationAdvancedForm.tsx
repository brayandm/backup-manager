"use client";

import React, { useEffect } from "react";

interface BackupConfigurationAdvancedFormProps {
  retentionPolicy: string;
  setRetentionPolicy: React.Dispatch<React.SetStateAction<string>>;
  compression: string;
  setCompression: React.Dispatch<React.SetStateAction<string>>;
  encryption: string;
  setEncryption: React.Dispatch<React.SetStateAction<string>>;
  integrityCheck: string;
  setIntegrityCheck: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationAdvancedForm({
  setMissingValues,
}: BackupConfigurationAdvancedFormProps) {
  return <></>;
}

export default BackupConfigurationAdvancedForm;
